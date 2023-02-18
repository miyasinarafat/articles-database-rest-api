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
use JeroenG\Explorer\Domain\Syntax\Matching;
use JeroenG\Explorer\Domain\Syntax\Term;
use JeroenG\Explorer\Domain\Syntax\Terms;
use Laravel\Scout\Builder as ScoutBuilder;

final class ArticleRepository implements ArticleRepositoryInterface
{
    public const CACHE_TAGS = [CacheTag::ARTICLE];

    /**
     * @param ArticleFilterItem|null $filterItems
     * @param ArticleOrderItem|null $orderItems
     * @param string|null $query
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getList(
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
            if (! $query) {
                $builder = Article::query();
            } else {
                $builder = Article::search($query);
            }

            if ($filterItems) {
                $this->applyFilter($builder, $filterItems, $query);
            }

            if ($orderItems) {
                $builder->orderBy($orderItems->getField(), $orderItems->getDirection());
            }

            if (! $query) {
                $result = $builder->paginate($perPage, ['*'], 'page', $page);
            } else {
                $result = $builder->paginate(perPage: $perPage, page: $page);
            }

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }

    /**
     * @param Builder|ScoutBuilder $builder
     * @param ArticleFilterItem $filter
     * @param string|null $query
     * @return void
     */
    private function applyFilter(Builder|ScoutBuilder $builder, ArticleFilterItem $filter, string $query = null): void
    {
        if ($categoryIds = $filter->getCategories()) {
            if (! $query) {
                $builder->whereIn('category_id', $categoryIds);
            } else {
                $builder->query(function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds);
                });
            }
        }

        if ($sourceIds = $filter->getSources()) {
            if (! $query) {
                $builder->whereIn('source_id', $sourceIds);
            } else {
                $builder->query(function ($query) use ($sourceIds) {
                    $query->whereIn('source_id', $sourceIds);
                });
            }
        }

        if ($authorIds = $filter->getAuthors()) {
            if (! $query) {
                $builder->whereIn('author_id', $authorIds);
            } else {
                $builder->query(function ($query) use ($authorIds) {
                    $query->whereIn('author_id', $authorIds);
                });
            }
        }

        if ($fromArticleDate = $filter->getFromArticleDate()) {
            if (! $query) {
                $builder->where('published_at', '>=', $fromArticleDate);
            } else {
                $builder->query(function ($query) use ($fromArticleDate) {
                    $query->where('published_at', '>=', $fromArticleDate);
                });
            }
        }

        if ($toArticleDate = $filter->getToArticleDate()) {
            if (! $query) {
                $builder->where('published_at', '<=', $toArticleDate);
            } else {
                $builder->query(function ($query) use ($toArticleDate) {
                    $query->where('published_at', '<=', $toArticleDate);
                });
            }
        }
    }
}
