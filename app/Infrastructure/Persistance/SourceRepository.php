<?php

namespace App\Infrastructure\Persistance;

use App\Domain\Source\Source;
use App\Domain\Source\SourceRepositoryInterface;
use App\Infrastructure\Cache\Cache;
use App\Infrastructure\Cache\CacheTag;
use Illuminate\Database\Eloquent\Collection;

final class SourceRepository implements SourceRepositoryInterface
{
    public const CACHE_TAGS = [CacheTag::SOURCE];

    /**
     * @inheritDoc
     */
    public function getList(): Collection
    {
        $cacheKey = Cache::generateCacheKey(__CLASS__, __METHOD__);

        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $result = Source::query()->orderBy('id')->get();

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function create(Source $source): ?Source
    {
        if (! $source->save()) {
            return null;
        }

        Cache::flushTagCache(CacheTag::SOURCE);

        return $source;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Source
    {
        $cacheKey = Cache::generateCacheKey(__METHOD__, 'source', (string)$id);

        /** @var Source $result */
        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $result = Source::query()->find($id);

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }
}
