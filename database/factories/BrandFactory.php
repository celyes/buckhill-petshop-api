<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Pet Smart',
                'The Farmer\'s Dog',
                'Pumpkin',
                'Vetcove',
                'Spot & Tango',
                'Chewy',
                'Skiptown',
                'Companion Protect',
                'TryFi',
                'Zesty Paws',
            ]),
        ];
    }
}
