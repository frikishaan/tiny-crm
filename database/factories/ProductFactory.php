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
    public function definition()
    {
        return [
            'name' => fake()->sentence(rand(2, 3)),
            'product_id' => 'PRO-' . strtoupper(Str::random(8)),
            'type' => rand(1, 2),
            'price' => fake()->numberBetween(500, 5000),
        ];
    }
}
