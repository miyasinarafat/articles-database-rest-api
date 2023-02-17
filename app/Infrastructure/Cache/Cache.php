<?php

namespace App\Infrastructure\Cache;

use Illuminate\Support\Facades\Cache as LaravelCache;
use JetBrains\PhpStorm\Pure;

final class Cache
{
    public const CACHE_MIN = 60;

    /**
     * @param array $args
     * @return string|null
     */
    public static function getCacheKey(array $args): ?string
    {
        if (count($args) === 0) {
            return null;
        }

        $key = '';
        foreach ($args as $arg) {
            $key .= '|' . $arg;
        }

        return md5($key);
    }

    /**
     * @param mixed $args
     * @return string|null
     */
    #[Pure]
    public static function generateCacheKey(string ...$args): ?string
    {
        return self::getCacheKey($args);
    }

    /**
     * @param string $cacheKey
     * @param array $cacheTags
     *
     * @return mixed
     */
    public static function readCache(string $cacheKey, array $cacheTags = []): mixed
    {
        $cacheTags = self::handleTags($cacheTags);

        return empty($cacheTags)
            ? LaravelCache::get($cacheKey)
            : LaravelCache::tags($cacheTags)->get($cacheKey);
    }

    /**
     * @param string $cacheKey
     * @param $outData
     * @param CacheTag[] $cacheTags
     * @param null $minutes
     *
     * @return mixed
     */
    public static function writeCache(
        string $cacheKey,
        $outData,
        array $cacheTags = [],
        $minutes = null
    ): mixed {
        $cacheTags = self::handleTags($cacheTags);

        if (! $minutes) {
            $minutes = self::CACHE_MIN;
        }

        if (empty($cacheTags)) {
            LaravelCache::put($cacheKey, $outData, $minutes * 60);
        } else {
            LaravelCache::tags($cacheTags)->put($cacheKey, $outData, $minutes * 60);
        }

        return $outData;
    }

    /**
     * @param string $cacheKey
     * @param $outData
     * @param CacheTag[] $cacheTags
     * @return mixed
     */
    public static function writePermanently(string $cacheKey, $outData, array $cacheTags = []): mixed
    {
        $cacheTags = self::handleTags($cacheTags);

        if (empty($cacheTags)) {
            LaravelCache::put($cacheKey, $outData);
        } else {
            LaravelCache::tags($cacheTags)->put($cacheKey, $outData);
        }

        return $outData;
    }

    /**
     * @param string|null $cacheKey
     * @param CacheTag[] $cacheTags
     */
    public static function flushCache(string $cacheKey = null, array $cacheTags = []): void
    {
        $cacheTags = self::handleTags($cacheTags);

        if (null === $cacheKey) {
            LaravelCache::tags($cacheTags)->flush();

            return;
        }

        if (count($cacheTags) > 0) {
            LaravelCache::tags($cacheTags)->forget($cacheKey);
        } else {
            LaravelCache::forget($cacheKey);
        }
    }

    /**
     * @param CacheTag $tag
     * @return bool
     */
    public static function flushTagCache(CacheTag $tag): bool
    {
        return LaravelCache::tags($tag->value)->flush();
    }

    /**
     * @param string $tag
     * @return bool
     */
    public static function flushEntityTagCache(string $tag): bool
    {
        return LaravelCache::tags($tag)->flush();
    }

    /**
     * @param CacheTag $tag
     * @param string $entityKey
     * @return string
     */
    public static function getEntityTag(CacheTag $tag, string $entityKey): string
    {
        return sprintf('%s_%s', $tag->value, $entityKey);
    }

    /**
     * @param array $tags
     * @return string[]
     */
    private static function handleTags(array $tags): array
    {
        return array_map(static fn ($tag) => $tag instanceof CacheTag ? $tag->value : $tag, $tags);
    }
}
