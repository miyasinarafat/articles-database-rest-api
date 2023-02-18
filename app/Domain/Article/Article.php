<?php

namespace App\Domain\Article;

use Database\Factories\Domain\Article\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JeroenG\Explorer\Application\Explored;
use Laravel\Scout\Searchable as ScoutSearchable;

class Article extends Model implements Explored
{
    use HasFactory;
    use ScoutSearchable;

    public $timestamps = false;
    protected $fillable = [
        'source_id',
        'category_id',
        'author_id',
        'title',
        'path',
        'content',
        'image_url',
        'source_url',
        'published_at',
    ];

    /**
     * For elasticsearch
     * @return string[]
     */
    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'title' => 'text',
            'path' => 'text',
            'content' => 'text',
            'published_at' => 'text',
        ];
    }

    /**
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'path' => $this->path,
            'content' => $this->content,
            'published_at' => $this->published_at,
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ArticleFactory::new();
    }
}
