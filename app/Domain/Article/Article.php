<?php

namespace App\Domain\Article;

use Database\Factories\Domain\Article\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use HasFactory;
    use Searchable;

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
            'content' => 'text',
            'published_at' => 'date',
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
