<?php

namespace Database\Factories\Domain\Source;

use App\Domain\Category\Category;
use App\Domain\Source\Source;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Source\Source>
 */
class SourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Source::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'category_id' => Category::factory()->create()->id,
            'name' => $name,
            'path' => Str::slug($name),
            'url' => $this->faker->url(),
        ];
    }
}
