<?php

namespace App\Infrastructure\Services\News;

use Illuminate\Http\Client\PendingRequest;

abstract class AbstractNewsApiClient implements NewsApiClientInterface
{
    public const CATEGORIES = [
        'business',
        'entertainment',
        'general',
        'health',
        'science',
        'sports',
        'technology',
    ];

    public function __construct(
        public readonly PendingRequest $client
    ) {
    }
}
