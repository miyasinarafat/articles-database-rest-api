<?php

namespace App\Http\Resources;

use App\Domain\Article\Article;
use App\Domain\Author\Author;
use App\Domain\Category\Category;
use App\Domain\Source\Source;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        //TODO:: refactor with repository
        /** @var Source $source */
        $source = Source::query()->find($this->resource->source_id);
        /** @var Category $category */
        $category = Category::query()->find($this->resource->category_id);
        /** @var Author $author */
        $author = Author::query()->find($this->resource->author_id);

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'path' => $this->resource->path,
            'content' => $this->resource->content,
            'imageUrl' => $this->resource->image_url,
            'sourceUrl' => $this->resource->source_url,
            'publishedAt' => $this->resource->published_at,
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