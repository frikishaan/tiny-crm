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
            'source' => fake()->numberBetween(1, 5),
            'estimated_revenue' => fake()->randomFloat(1, 10000, 100000),
            'description' => fake()->paragraph(10),
            'status' => fake()->numberBetween(1, 4),
            'created_at' => fake()->dateTimeBetween('-1 years')
        ];
    }
}
