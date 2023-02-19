<?php

namespace App\Domain;

use App\Models\User;
use Database\Factories\SettingsFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/***
 * @property-read int id
 * @property int user_id
 * @property array sources
 * @property array categories
 * @property array authors
 * @property-read User $user
 */
final class Settings extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['user_id', 'sources', 'categories', 'authors'];
    protected $casts = [
        'sources' => 'array',
        'categories' => 'array',
        'authors' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return SettingsFactory::new();
    }
}
