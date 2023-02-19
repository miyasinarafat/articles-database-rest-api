<?php

namespace App\Infrastructure\Persistance;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepositoryInterface;
use App\Infrastructure\Cache\Cache;
use App\Infrastructure\Cache\CacheTag;
use Illuminate\Database\Eloquent\Collection;

final class AuthorRepository implements AuthorRepositoryInterface
{
    public const CACHE_TAGS = [CacheTag::AUTHOR];

    /**
     * @inheritDoc
     */
    public function getList(): Collection
    {
        $cacheKey = Cache::generateCacheKey(__CLASS__, __METHOD__);

        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $result = Author::query()->orderBy('id')->get();

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function create(Author $author): ?Author
    {
        $dbAuthor = Author::query()->firstOrCreate(
            [
                'name' => $author->name,
            ],
            [
                'name' => $author->name,
                'path' => $author->path,
            ]
        );

        if (! $dbAuthor) {
            return null;
        }

        Cache::flushTagCache(CacheTag::AUTHOR);

        return $author;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Author
    {
        $cacheKey = Cache::generateCacheKey(__METHOD__, 'author', (string)$id);

        /** @var Author $result */
        if (! $result = Cache::readCache($cacheKey, self::CACHE_TAGS)) {
            $result = Author::query()->find($id);

            Cache::writePermanently($cacheKey, $result, self::CACHE_TAGS);
        }

        return $result;
    }
}
