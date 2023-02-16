<?php

namespace Database\Factories\Domain\Article;

use App\Domain\Article\Article;
use App\Domain\Author\Author;
use App\Domain\Category\Category;
use App\Domain\Source\Source;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Article\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->name();

        return [
            'source_id' => Source::factory()->create()->id,
            'category_id' => Category::factory()->create()->id,
            'author_id' => Author::factory()->create()->id,
            'title' => $title,
            'path' => Str::slug($title),
            'content' => $this->faker->paragraphs(),
            'image_url' => $this->faker->url(),
            'source_url' => $this->faker->url(),
            'published_at' => now(),
        ];
    }
}
