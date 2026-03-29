<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'job_id' => Job::inRandomOrder()->first()->id,
            'freelancer_id' => User::where('role', 'freelancer')->inRandomOrder()->first()->id,
            'bid_amount' => fake()->randomFloat(2, 50, 5000),
            'cover_letter' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}