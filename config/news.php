<?php

return [
    'integration' => [
        'newsapiorg' => [
            'token' => env('NEWS_API_ORG_API_TOKEN'),
            'base_url' => 'https://newsapi.org/v2',
            'articles_path' => '/everything',
            'sources_path' => '/sources',
        ],
    ],
];
