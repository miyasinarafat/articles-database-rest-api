<?php

namespace App\Domain\Author;

use Database\Factories\Domain\Author\AuthorFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name', 'path'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return AuthorFactory::new();
    }
}
