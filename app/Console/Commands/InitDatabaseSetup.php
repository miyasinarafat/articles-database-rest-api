<?php

namespace App\Console\Commands;

use App\Domain\Category\Category;
use App\Infrastructure\Services\News\NewsApiClientInterface;
use App\Infrastructure\Services\News\NewsApiOrg\NewsApiOrgApiClient;
use App\Jobs\SourceStoreJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Throwable;

class InitDatabaseSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:InitDatabaseSetup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initially setup database tables with data.';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(): void
    {
        /** @var NewsApiOrgApiClient $newsApi */
        $newsApi = resolve(NewsApiClientInterface::class);

        /** Saving categories */
        $this->saveCategories($newsApi);

        /** Saving sources */
        $this->saveSources($newsApi);
    }

    /**
     * @param NewsApiOrgApiClient $newsApi
     * @return void
     */
    private function saveCategories(NewsApiOrgApiClient $newsApi): void
    {
        $this->info('Start saving categories:');
        $bar = $this->output->createProgressBar(count($newsApi::CATEGORIES));
        $bar->start();

        foreach ($newsApi::CATEGORIES as $category) {
            //TODO:: refactor with repository
            Category::query()->create([
                'name' => $category,
                'path' => Str::slug($category),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * @param NewsApiOrgApiClient $newsApi
     * @return void
     * @throws Throwable
     */
    private function saveSources(NewsApiOrgApiClient $newsApi): void
    {
        $this->info('Start saving sources:');
        $bar = $this->output->createProgressBar(count($newsApi::CATEGORIES));
        $bar->start();

        $sourcesJobs = [];
        foreach ($newsApi::CATEGORIES as $category) {
            $sourcesJobs[] = new SourceStoreJob($category);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        Bus::batch($sourcesJobs)
            ->name('InitDatabaseSetup::sources-batch-jobs')
            ->allowFailures()
            ->dispatch();
    }
}
