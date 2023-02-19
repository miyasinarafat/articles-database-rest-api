<?php

namespace App\Domain\Category;

use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getList(): Collection;

    /**
     * @param Category $category
     * @return Category|null
     */
    public function create(Category $category): ?Category;

    /**
     * @param int $id
     * @return Category|null
     */
    public function getById(int $id): ?Category;
}
