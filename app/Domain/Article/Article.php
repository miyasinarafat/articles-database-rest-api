<?php

namespace App\Domain\Article;

use App\Domain\Author\Author;
use App\Domain\Category\Category;
use App\Domain\Source\Source;
use Database\Factories\Domain\Article\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JeroenG\Explorer\Application\Explored;
use Laravel\Scout\Searchable as ScoutSearchable;

/***
 * @property-read int id
 * @property int source_id
 * @property int category_id
 * @property int author_id
 * @property string title
 * @property string content
 * @property string image_url
 * @property string source_url
 * @property string published_at
 * @property-read Source $source
 * @property-read Category $category
 * @property-read Author $author
 */
final class Article extends Model implements Explored
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
            'id' => $this->id,
            'title' => $this->title,
            'path' => $this->path,
            'content' => $this->content,
            'published_at' => $this->published_at,
        ];
    }

    /**
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'author_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'category_id', 'id');
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
