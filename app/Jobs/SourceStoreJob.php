<?php

namespace App\Jobs;

use App\Domain\Source\Source;
use App\Infrastructure\Services\News\NewsApiClientInterface;
use App\Infrastructure\Services\News\NewsApiOrg\NewsApiOrgApiClient;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\ParameterBag;

class SourceStoreJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private const SOURCE_COUNT = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $category,
        public int $categoryId,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var NewsApiOrgApiClient $newsApi */
        $newsApi = resolve(NewsApiClientInterface::class);

        $parameters = new ParameterBag();
        $parameters->set('category', $this->category);
        $sources = $newsApi->getSources($parameters);

        if (! $sources) {
            return;
        }

        for ($sourceIndex = 0; $sourceIndex < self::SOURCE_COUNT; $sourceIndex++) {
            if (! isset($sources['sources'][$sourceIndex])) {
                continue;
            }

            $source = $sources['sources'][$sourceIndex];

            //TODO:: refactor with repository
            Source::query()->create([
                'category_id' => $this->categoryId,
                'name' => $source['name'],
                'path' => $source['id'],
                'url' => $source['url'],
            ]);
        }
    }
}
