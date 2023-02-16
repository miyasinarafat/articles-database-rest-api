<?php

namespace Database\Factories;

use App\Domain\Author\Author;
use App\Domain\Category\Category;
use App\Domain\Settings;
use App\Domain\Source\Source;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Settings>
 */
class SettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Settings::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var array $sources */
        $sources = Source::factory()->count(5)->create()->pluck('id');
        /** @var array $categories */
        $categories = Category::factory()->count(5)->create()->pluck('id');
        /** @var array $authors */
        $authors = Author::factory()->count(5)->create()->pluck('id');

        return [
            'user_id' => User::factory()->create()->id,
            'sources' => $sources,
            'categories' => $categories,
            'authors' => $authors,
        ];
    }
}
