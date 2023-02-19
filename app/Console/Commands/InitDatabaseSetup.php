<?php

namespace App\Console\Commands;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
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
        /** @var CategoryRepositoryInterface $categoryRepository */
        $categoryRepository = resolve(CategoryRepositoryInterface::class);

        $this->info('Start saving categories + sources:');
        $bar = $this->output->createProgressBar(count($newsApi::CATEGORIES));
        $bar->start();

        /** Saving categories */
        $sourcesJobs = [];
        foreach ($newsApi::CATEGORIES as $category) {
            $candidateCategory = (new Category())->fill([
                'name' => ucfirst($category),
                'path' => Str::slug($category),
            ]);
            /** @var Category $dbCategory */
            $dbCategory = $categoryRepository->create($candidateCategory);

            /** Saving sources */
            $sourcesJobs[] = new SourceStoreJob($category, $dbCategory->id);

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
