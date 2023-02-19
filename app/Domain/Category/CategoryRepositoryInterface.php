<?php

namespace App\Domain\Category;

interface CategoryRepositoryInterface
{
    /**
     * @param Category $category
     * @return Category|null
     */
    public function create(Category $category): ?Category;
}
