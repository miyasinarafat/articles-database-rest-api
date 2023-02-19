<?php

namespace App\Jobs;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Author\Author;
use App\Domain\Source\Source;
use App\Domain\Source\SourceRepositoryInterface;
use App\Infrastructure\Cache\Cache;
use App\Infrastructure\Cache\CacheTag;
use App\Infrastructure\Services\News\NewsApiClientInterface;
use App\Infrastructure\Services\News\NewsApiOrg\NewsApiOrgApiClient;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\ParameterBag;

class ArticlesStoreJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $sourceId,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var NewsApiOrgApiClient $newsApi */
        $newsApi = resolve(NewsApiClientInterface::class);
        /** @var SourceRepositoryInterface $sourceRepository */
        $sourceRepository = resolve(SourceRepositoryInterface::class);
        /** @var ArticleRepositoryInterface $articleRepository */
        $articleRepository = resolve(ArticleRepositoryInterface::class);

        /** @var Source $source */
        $source = $sourceRepository->getById($this->sourceId);

        $parameters = new ParameterBag();
        $parameters->set('source', $source->path);
        $articles = $newsApi->getArticles($parameters);

        if (! $articles && ! isset($articles['articles'])) {
            return;
        }

        foreach ($articles['articles'] as $article) {
            //TODO:: refactor with repository
            try {
                $author = Author::query()->firstOrCreate([
                    'name' => $article['author'],
                ], [
                    'name' => $article['author'],
                    'path' => Str::slug($article['author']),
                ]);
            } catch (Exception $exception) {
            }

            $candidateArticle = (new Article())->fill([
                'source_id' => $source->id,
                'category_id' => $source->category_id,
                'author_id' => $author->id ?? null,
                'title' => $article['title'],
                'path' => Str::slug($article['title']),
                'content' => $article['description'],
                'image_url' => $article['urlToImage'],
                'source_url' => $article['url'],
                'published_at' => Carbon::parse($article['publishedAt'])->toDateTimeString(),
            ]);

            $articleRepository->create($candidateArticle);
        }

    }
}
