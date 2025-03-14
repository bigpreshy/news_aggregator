<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Article::class;

    public function definition()
    {
        return [
            'source_id' => $this->faker->uuid,
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['business', 'sports', 'technology']),
            'author' => $this->faker->name,
            'source_name' => $this->faker->company,
            'published_at' => $this->faker->dateTimeThisYear,
            'url' => $this->faker->url
        ];
    }
}
