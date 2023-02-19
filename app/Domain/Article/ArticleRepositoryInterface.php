<?php

namespace App\Domain\Article;

use App\Domain\Objects\ArticleFilterItem;
use App\Domain\Objects\ArticleOrderItem;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    /**
     * @param ArticleFilterItem|null $filterItems
     * @param ArticleOrderItem|null $orderItems
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getList(
        ?ArticleFilterItem $filterItems = null,
        ?ArticleOrderItem $orderItems = null,
        int $page = 1,
        int $perPage = 15,
    ): LengthAwarePaginator;

    /**
     * @param ArticleFilterItem|null $filterItems
     * @param ArticleOrderItem|null $orderItems
     * @param string|null $query
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchList(
        ?ArticleFilterItem $filterItems = null,
        ?ArticleOrderItem $orderItems = null,
        string $query = null,
        int $page = 1,
        int $perPage = 15,
    ): LengthAwarePaginator;
}
