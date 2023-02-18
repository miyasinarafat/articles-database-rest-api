<?php

namespace App\Console\Commands;

use App\Domain\Article\Article;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Indexing all articles:');
        $bar = $this->output->createProgressBar(Article::query()->count());
        $bar->start();

        $client = ClientBuilder::create()
            ->setHosts([
                sprintf(
                    '%s:%s',
                    config('explorer.connection.host'),
                    config('explorer.connection.port')
                ),
            ])
            ->build();

        /** @var Article $article */
        foreach (Article::query()->cursor() as $article) {
            $client->index([
                'index' => $article->getTable(),
                'type' => $article->getTable(),
                'id' => $article->getKey(),
                'body' => $article->toSearchableArray(),
            ]);

            $bar->advance();
        }

        $bar->finish();
    }
}
