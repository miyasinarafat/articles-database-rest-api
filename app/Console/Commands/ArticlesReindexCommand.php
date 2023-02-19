<?php

namespace App\Console\Commands;

use App\Domain\Article\Article;
use Illuminate\Console\Command;
use JeroenG\Explorer\Application\DocumentAdapterInterface;
use JeroenG\Explorer\Application\Explored;
use JeroenG\Explorer\Application\IndexAdapterInterface;
use JeroenG\Explorer\Domain\IndexManagement\IndexConfigurationRepositoryInterface;

class ArticlesReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:ArticlesReindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all articles to Elasticsearch';

    public function __construct(
        private IndexAdapterInterface $indexAdapter,
        private DocumentAdapterInterface $documentAdapter,
        private IndexConfigurationRepositoryInterface $indexConfigurationRepository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        /** Getting articles by chunk */
        $models = Article::query()->lazy();

        $this->info('Indexing all articles:');
        $bar = $this->output->createProgressBar($models->count());
        $bar->start();

        /** @var Explored $firstModel */
        $firstModel = $models->first();

        $indexConfiguration = $this->indexConfigurationRepository->findForIndex($firstModel->searchableAs());
        $this->indexAdapter->ensureIndex($indexConfiguration);
        $indexName = $indexConfiguration->getWriteIndexName();

        foreach ($models as $article) {
            $this->documentAdapter->update($indexName, $article->id, $article->toSearchableArray());

            $bar->advance();
        }

        $bar->finish();
    }
}
