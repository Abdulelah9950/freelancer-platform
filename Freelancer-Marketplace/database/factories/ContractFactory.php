<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    public function definition(): array
    {
        $job = Job::where('status', 'closed')->inRandomOrder()->first() ?? Job::factory()->create(['status' => 'closed']);
        
        return [
            'job_id' => $job->id,
            'client_id' => $job->client_id,
            'freelancer_id' => User::where('role', 'freelancer')->inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['active', 'completed', 'terminated']),
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->optional()->dateTimeBetween('now', '+3 months'),
        ];
    }
}