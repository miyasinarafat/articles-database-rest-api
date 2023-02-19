<?php

namespace App\Infrastructure\Persistance;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Objects\ArticleFilterItem;
use App\Domain\Objects\ArticleOrderItem;
use App\Infrastructure\Cache\Cache;
use App\Infrastructure\Cache\CacheTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Scout\Builder as ScoutBuilder;

final class ArticleRepository implements ArticleRepositoryInterface
{
    public const CACHE_TAGS = [CacheTag::ARTICLE];

    /**
     * @param ArticleFilterItem|null $filterItems
     * @param ArticleOrderItem|null $orderItems
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getList(
        ?ArticleFilterItem $filterItems = null,
        ?ArticleOrderItem  $orderItems = null,
        int $page = 1,
        int $perPage = 15,
    ): LengthAwarePaginator {
        $cacheKey = Cache::generateCacheKey(
            __CLASS__,
            __METHOD__,
            $page,
            $perPage,
            (string)$orderItems,
            (string)$filterItems,
        );

        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $builder = Article::query();

            if ($filterItems) {
                $this->applyFilter($builder, $filterItems);
            }

            if (! $orderItems) {
                $builder->orderByDesc('published_at');
            } else {
                $builder->orderBy($orderItems->getField(), $orderItems->getDirection());
            }

            $result = $builder->paginate($perPage, ['*'], 'page', $page);

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }

    /**
     * @param ArticleFilterItem|null $filterItems
     * @param ArticleOrderItem|null $orderItems
     * @param string|null $query
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchList(
        ?ArticleFilterItem $filterItems = null,
        ?ArticleOrderItem  $orderItems = null,
        string $query = null,
        int $page = 1,
        int $perPage = 15,
    ): LengthAwarePaginator {
        $cacheKey = Cache::generateCacheKey(
            __CLASS__,
            __METHOD__,
            $page,
            $perPage,
            (string)$query,
            (string)$orderItems,
            (string)$filterItems,
        );

        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $builder = Article::search($query);

            if ($filterItems) {
                $this->applySearchFilter($builder, $filterItems);
            }

            if ($orderItems) {
                $builder->orderBy($orderItems->getField(), $orderItems->getDirection());
            }

            $result = $builder->paginate(perPage: $perPage, page: $page);

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }

    /**
     * @param Builder $builder
     * @param ArticleFilterItem $filter
     * @return void
     */
    private function applyFilter(Builder $builder, ArticleFilterItem $filter): void
    {
        if ($categoryIds = $filter->getCategories()) {
            $builder->whereIn('category_id', $categoryIds);
        }

        if ($sourceIds = $filter->getSources()) {
            $builder->whereIn('source_id', $sourceIds);
        }

        if ($authorIds = $filter->getAuthors()) {
            $builder->whereIn('author_id', $authorIds);
        }

        if ($fromArticleDate = $filter->getFromArticleDate()) {
            $builder->where('published_at', '>=', $fromArticleDate);
        }

        if ($toArticleDate = $filter->getToArticleDate()) {
            $builder->where('published_at', '<=', $toArticleDate);
        }
    }

    /**
     * @param ScoutBuilder $builder
     * @param ArticleFilterItem $filter
     * @return void
     */
    private function applySearchFilter(ScoutBuilder $builder, ArticleFilterItem $filter): void
    {
        if ($categoryIds = $filter->getCategories()) {
            $builder->query(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            });
        }

        if ($sourceIds = $filter->getSources()) {
            $builder->query(function ($query) use ($sourceIds) {
                $query->whereIn('source_id', $sourceIds);
            });
        }

        if ($authorIds = $filter->getAuthors()) {
            $builder->query(function ($query) use ($authorIds) {
                $query->whereIn('author_id', $authorIds);
            });
        }

        if ($fromArticleDate = $filter->getFromArticleDate()) {
            $builder->query(function ($query) use ($fromArticleDate) {
                $query->where('published_at', '>=', $fromArticleDate);
            });
        }

        if ($toArticleDate = $filter->getToArticleDate()) {
            $builder->query(function ($query) use ($toArticleDate) {
                $query->where('published_at', '<=', $toArticleDate);
            });
        }
    }
}
