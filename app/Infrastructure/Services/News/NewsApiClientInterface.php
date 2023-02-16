<?php

namespace App\Infrastructure\Services\News;

use Symfony\Component\HttpFoundation\ParameterBag;

interface NewsApiClientInterface
{
    /**
     * @param ParameterBag $parameters
     * @return array|null
     */
    public function getArticles(ParameterBag $parameters): ?array;
}
