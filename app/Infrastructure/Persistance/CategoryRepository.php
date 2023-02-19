<?php

namespace App\Infrastructure\Persistance;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @param Category $category
     * @return Category|null
     */
    public function create(Category $category): ?Category
    {
        if (! $category->save()) {
            return null;
        }

        return $category;
    }
}
