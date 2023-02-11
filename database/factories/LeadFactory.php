<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
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
            'estimated_revenue' => fake()->numberBetween(10000, 1000000),
            'description' => fake()->realText(200),
            'status' => fake()->numberBetween(1, 4),
            'created_at' => fake()->dateTimeBetween('-2 years')
        ];
    }
}
