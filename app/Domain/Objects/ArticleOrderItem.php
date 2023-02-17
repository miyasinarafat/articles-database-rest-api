<?php

namespace App\Domain\Objects;

final class ArticleOrderItem extends BaseOrderItem
{
    /**
     * @var array|string[]
     */
    protected static array $fields = [
        'id',
        'published_at',
    ];
}
