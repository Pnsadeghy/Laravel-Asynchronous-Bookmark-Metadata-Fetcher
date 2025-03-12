<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(1, true),
            'description' => $this->faker->sentence(5, true),
            'body' => $this->faker->paragraphs(5, true),
            'url' => $this->faker->unique()->url(),
            'is_published' => true,
            'published_at' => now()
        ];
    }

    /**
     * Inactive model
     */
    public function unpublish(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
