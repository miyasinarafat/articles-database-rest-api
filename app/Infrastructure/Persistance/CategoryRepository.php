<?php

namespace App\Infrastructure\Persistance;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Infrastructure\Cache\Cache;
use App\Infrastructure\Cache\CacheTag;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public const CACHE_TAGS = [CacheTag::CATEGORY];

    /**
     * @inheritDoc
     */
    public function getList(): Collection
    {
        $cacheKey = Cache::generateCacheKey(__CLASS__, __METHOD__);

        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $result = Category::query()->orderBy('id')->get();

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function create(Category $category): ?Category
    {
        if (! $category->save()) {
            return null;
        }

        Cache::flushTagCache(CacheTag::CATEGORY);

        return $category;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Category
    {
        $cacheKey = Cache::generateCacheKey(__METHOD__, 'category', (string)$id);

        /** @var Category $result */
        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $result = Category::query()->find($id);

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }
}
