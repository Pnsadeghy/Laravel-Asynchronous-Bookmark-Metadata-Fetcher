<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsReaderSource>
 */
class NewsReaderSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'is_enabled' => true,
            'api_url' => '',
            'api_key' => '',
            'reader_class' => '',
            'request_data' => []
        ];
    }

    /**
     * Inactive model
     */
    public function disable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_enabled' => false,
        ]);
    }
}
