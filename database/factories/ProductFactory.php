<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title,
            'price' => fake()->randomFloat(2),
            'description' => fake()->text(),
            'metadata' => [
                'image' => (string) Str::uuid(),
                'brand' => (string) Str::uuid()
            ],
        ];
    }
}
