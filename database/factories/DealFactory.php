<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(fake()->numberBetween(2, 4)),
            'customer_id' => fake()->numberBetween(1, 25),
            'estimated_revenue' => fake()->randomFloat(1, 10000, 100000),
            'actual_revenue' => fake()->randomFloat(1, 10000, 100000),
            'status' => fake()->numberBetween(1, 3)
        ];
    }
}
