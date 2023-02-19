<?php

namespace App\Domain\Author;

use Illuminate\Database\Eloquent\Collection;

interface AuthorRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getList(): Collection;

    /**
     * @param Author $author
     * @return Author|null
     */
    public function create(Author $author): ?Author;

    /**
     * @param int $id
     * @return Author|null
     */
    public function getById(int $id): ?Author;
}
