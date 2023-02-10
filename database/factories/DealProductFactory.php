<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DealProduct>
 */
class DealProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => fake()->numberBetween(1, 25),
            'quantity' => fake()->numberBetween(1, 100),
            'price_per_unit' => fake()->numberBetween(500, 5000),
            'total_amount' => fake()->numberBetween(500, 5000)
        ];
    }
}
