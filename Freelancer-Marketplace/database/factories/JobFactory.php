<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => User::where('role', 'client')->inRandomOrder()->first()->id,
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'budget' => fake()->randomFloat(2, 100, 10000),
            'status' => fake()->randomElement(['open', 'closed']),
        ];
    }
}