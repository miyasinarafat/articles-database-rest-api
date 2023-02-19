<?php

namespace App\Console\Commands;

use App\Domain\Source\SourceRepositoryInterface;
use App\Jobs\ArticlesStoreJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Throwable;

class RetrieveNewsApiOrgArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:RetrieveNewsApiOrgArticles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieving articles from newsapi.org';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(): void
    {
        /** @var SourceRepositoryInterface $sourceRepository */
        $sourceRepository = resolve(SourceRepositoryInterface::class);
        $sources = $sourceRepository->getList();

        /** Saving articles */
        $this->info('Start saving articles and processing by sources:');
        $bar = $this->output->createProgressBar($sources->count());
        $bar->start();

        $articlesJobs = [];
        foreach ($sources as $source) {
            $articlesJobs[] = new ArticlesStoreJob($source->id);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        Bus::batch($articlesJobs)
            ->name('RetrieveNewsApiOrgArticles::articles-batch-jobs')
            ->allowFailures()
            ->dispatch();
    }
}
