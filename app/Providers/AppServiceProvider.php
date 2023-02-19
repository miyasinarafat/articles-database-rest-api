<?php

namespace App\Providers;

use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Author\AuthorRepositoryInterface;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\SettingsRepositoryInterface;
use App\Domain\Source\SourceRepositoryInterface;
use App\Infrastructure\Persistance\ArticleRepository;
use App\Infrastructure\Persistance\AuthorRepository;
use App\Infrastructure\Persistance\CategoryRepository;
use App\Infrastructure\Persistance\SettingsRepository;
use App\Infrastructure\Persistance\SourceRepository;
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
        /** News provider binding */
        $this->app->bind(NewsApiClientInterface::class, function ($app, $params) {
            if (isset($params['provider_type'])) {
                return $params['provider_type'] === NewsApiProvider::NEWSAPIORG
                    ? $app->make(NewsApiOrgApiClient::class)
                    : ''; //TODO:: For upcoming news provider api instance.
            }

            return $app->make(NewsApiOrgApiClient::class);
        });

        /** Repository binding */
        $this->app->singleton(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->singleton(SourceRepositoryInterface::class, SourceRepository::class);
        $this->app->singleton(AuthorRepositoryInterface::class, AuthorRepository::class);
        $this->app->singleton(SettingsRepositoryInterface::class, SettingsRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
