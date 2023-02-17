<?php

namespace App\Http\Resources;

class ArticleCollection extends PaginationCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ArticleResource::class;
}
