<?php

namespace App\Domain\Source;

use App\Domain\Category\Category;
use Database\Factories\Domain\Source\SourceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/***
 * @property-read int id
 * @property int category_id
 * @property string name
 * @property string path
 * @property string url
 * @property-read Category $category
 */
final class Source extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['category_id', 'name', 'path', 'url'];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return SourceFactory::new();
    }
}
