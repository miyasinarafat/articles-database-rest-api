<?php

namespace App\Domain\Source;

use Database\Factories\Domain\Source\SourceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'path', 'url'];

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
