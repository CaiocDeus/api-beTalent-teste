<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // TODO Tentar adicionar Faker OpenAI https://laravel-news.com/laravel-faker-openai
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'amount' => fake()->randomFloat(),
        ];
    }
}
