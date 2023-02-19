<?php

namespace App\Http\Resources;

use App\Domain\Article\Article;
use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepositoryInterface;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Source\Source;
use App\Domain\Source\SourceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @property-read Article $resource
 */
class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var SourceRepositoryInterface $sourceRepository */
        $sourceRepository = resolve(SourceRepositoryInterface::class);
        /** @var CategoryRepositoryInterface $categoryRepository */
        $categoryRepository = resolve(CategoryRepositoryInterface::class);
        /** @var AuthorRepositoryInterface $authorRepository */
        $authorRepository = resolve(AuthorRepositoryInterface::class);

        /** @var Source $source */
        $source = $sourceRepository->getById($this->resource->source_id);
        /** @var Category $category */
        $category = $categoryRepository->getById($this->resource->category_id);
        /** @var Author $author */
        $author = $authorRepository->getById($this->resource->author_id);

        return [
            'id' => $this->resource->id,
            'title' => Str::limit($this->resource->title, 45),
            'path' => $this->resource->path,
            'content' => $this->resource->content,
            'imageUrl' => $this->resource->image_url,
            'sourceUrl' => $this->resource->source_url,
            'publishedAt' => Carbon::parse($this->resource->published_at)->diffForHumans(),
            'source' => [
                'name' => $source->name,
                'path' => $source->path,
            ],
            'category' => [
                'name' => $category->name,
                'path' => $category->path,
            ],
            'author' => null !== $author
                ? ['name' => $author->name, 'path' => $author->path]
                : null,
        ];
    }
}
