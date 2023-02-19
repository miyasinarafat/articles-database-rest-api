<?php

namespace App\Infrastructure\Cache;

enum CacheTag: string
{
    case ARTICLE = 'article';
    case SOURCE = 'source';
    case CATEGORY = 'category';
    case AUTHOR = 'author';
    case SETTINGS = 'settings';
}
