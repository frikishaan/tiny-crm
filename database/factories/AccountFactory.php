<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->safeEmail(),

            // added en_IN locale to avoid brackets in phone number
            'phone' => fake('en_IN')->phoneNumber(),
            'address' => fake()->address(),
            'total_sales' => fake()->numberBetween(0, 1000000)
        ];
    }
}
