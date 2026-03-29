<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Job;
use App\Models\Proposal;
use App\Models\Skill;
use App\Models\Contract;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create skills
        $skills = [
            'PHP', 'JavaScript', 'Python', 'Java', 'C++', 
            'React', 'Vue.js', 'Laravel', 'WordPress', 'UI/UX Design',
            'Graphic Design', 'Content Writing', 'Digital Marketing', 'SEO', 'Data Entry'
        ];

        foreach ($skills as $skillName) {
            Skill::create(['name' => $skillName]);
        }

        // Create 10 freelancers
        $freelancers = User::factory(10)->freelancer()->create();
        
        // Assign random skills to freelancers
        $skillIds = Skill::pluck('id')->toArray();
        foreach ($freelancers as $freelancer) {
            $freelancer->skills()->attach(
                fake()->randomElements($skillIds, fake()->numberBetween(1, 5))
            );
        }

        // Create 10 clients
        $clients = User::factory(10)->client()->create();

        // Create 20 jobs
        $jobs = Job::factory(20)->create();

        // Create 40 proposals
        $proposals = Proposal::factory(40)->create();

        // Create some contracts for accepted proposals
        $acceptedProposals = Proposal::where('status', 'accepted')->take(5)->get();
        foreach ($acceptedProposals as $proposal) {
            Contract::factory()->create([
                'job_id' => $proposal->job_id,
                'client_id' => $proposal->job->client_id,
                'freelancer_id' => $proposal->freelancer_id,
            ]);
        }
    }
}