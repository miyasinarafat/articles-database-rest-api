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

        if ($fromOrderTime = $filter->getFromOrderTime()) {
            $builder->where('published_at', '>=', $fromOrderTime);
        }

        if ($toOrderTime = $filter->getToOrderTime()) {
            $builder->where('published_at', '<=', $toOrderTime);
        }
    }
}
