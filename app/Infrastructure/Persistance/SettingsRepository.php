<?php

namespace App\Infrastructure\Persistance;

use App\Domain\Settings;
use App\Domain\SettingsRepositoryInterface;
use App\Infrastructure\Cache\Cache;
use App\Infrastructure\Cache\CacheTag;

final class SettingsRepository implements SettingsRepositoryInterface
{
    public const CACHE_TAGS = [CacheTag::SETTINGS];

    /**
     * @inheritDoc
     */
    public function update(Settings $settings): ?Settings
    {
        /** @var Settings $dbSettings */
        $dbSettings = Settings::query()->updateOrCreate([
            'user_id' => $settings->user_id,
        ], [
            'user_id' => $settings->user_id,
            'sources' => $settings->sources,
            'categories' => $settings->categories,
            'authors' => $settings->authors,
        ]);

        if (! $dbSettings->save()) {
            return null;
        }

        $cacheKey = Cache::generateCacheKey('settings', (string)$settings->user_id);
        Cache::flushCache($cacheKey);
        Cache::flushTagCache(CacheTag::ARTICLE);

        return $dbSettings;
    }

    /**
     * @inheritDoc
     */
    public function getByUserId(int $userId): ?Settings
    {
        $cacheKey = Cache::generateCacheKey('settings', (string)$userId);

        /** @var Settings $result */
        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $result = Settings::query()->where('user_id', $userId)->first();

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }
}
