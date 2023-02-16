<?php

namespace App\Providers;

use App\Infrastructure\Services\News\NewsApiClientInterface;
use App\Infrastructure\Services\News\NewsApiOrg\NewsApiOrgApiClient;
use App\Infrastructure\Services\News\NewsApiProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NewsApiClientInterface::class, function ($app, $params) {
            if (isset($params['provider_type'])) {
                return $params['provider_type'] === NewsApiProvider::NEWSAPIORG
                    ? $app->make(NewsApiOrgApiClient::class)
                    : ''; //TODO:: For upcoming news provider api instance.
            }

            return $app->make(NewsApiOrgApiClient::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
