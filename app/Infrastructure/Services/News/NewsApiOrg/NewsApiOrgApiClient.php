<?php

namespace App\Infrastructure\Services\News\NewsApiOrg;

use App\Infrastructure\Services\News\AbstractNewsApiClient;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\ParameterBag;

class NewsApiOrgApiClient extends AbstractNewsApiClient
{
    /**
     * @param ParameterBag $parameters
     * @return array|null
     */
    public function getSources(ParameterBag $parameters): ?array
    {
        $response = $this->client
            ->withToken(config('news.integration.newsapiorg.token'))
            ->get(sprintf(
                '%s%s?category=%s',
                config('news.integration.newsapiorg.base_url'),
                config('news.integration.newsapiorg.sources_path'),
                $parameters->get('category'),
            ));

        if (! $response->successful()) {
            if ($response->status() !== 404) {
                Log::error(sprintf(
                    'NewsApiOrgApiClient::getSources with category %s: %s response: %s',
                    $parameters->get('category'),
                    $response->status(),
                    $response->body(),
                ));
            }

            return null;
        }

        $sources = $response->json();

        if (isset($sources['sources']) && count($sources['sources']) === 0) {
            return null;
        }

        return $sources;
    }

    public function getArticles(ParameterBag $parameters): ?array
    {
        // TODO: Implement getArticles() method.
        return [];
    }
}
