<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use App\Mail\NewJobNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class JobNotificationService
{
  /**
 * Send job notifications to freelancers with matching skills.
 *
 * @param \App\Models\Job $job
 * @return void
 */
public function notifyMatchingFreelancers(Job $job)
{
    // Log job details for debugging
    Log::info('Starting job notifications', [
        'job_id' => $job->id,
        'job_title' => $job->title,
        'job_skills_count' => $job->skills ? $job->skills->count() : 0
    ]);

    // Get all freelancers
    $freelancers = User::where('role', 'freelancer')
        ->with('skills')
        ->get();

    Log::info('Total freelancers found', ['count' => $freelancers->count()]);

    // Get job skills
    $jobSkills = $job->skills;

    if (!$jobSkills || $jobSkills->isEmpty()) {
        Log::info('Job has no skills attached, skipping notifications', ['job_id' => $job->id]);
        return;
    }

    $notifiedCount = 0;

    foreach ($freelancers as $freelancer) {
        // Log freelancer data
        Log::info('Checking freelancer', [
            'freelancer_id' => $freelancer->id,
            'freelancer_email' => $freelancer->email,
            'freelancer_skills_count' => $freelancer->skills ? $freelancer->skills->count() : 0
        ]);

        // Find matching skills between freelancer and job
        $matchingSkills = collect();
        
        if ($freelancer->skills && $freelancer->skills->isNotEmpty()) {
            $matchingSkills = $freelancer->skills->filter(function ($skill) use ($jobSkills) {
                return $jobSkills->contains('id', $skill->id);
            });
        }

        Log::info('Matching skills found', [
            'freelancer_id' => $freelancer->id,
            'matching_count' => $matchingSkills->count()
        ]);

        // If freelancer has at least one matching skill, send notification
        if ($matchingSkills->isNotEmpty()) {
            try {
                Log::info('Attempting to send email', [
                    'to' => $freelancer->email,
                    'job_id' => $job->id
                ]);

                Mail::to($freelancer->email)->send(
                    new NewJobNotification($job, $freelancer, $matchingSkills)
                );
                
                $notifiedCount++;
                Log::info('Email sent successfully', [
                    'freelancer_id' => $freelancer->id,
                    'freelancer_email' => $freelancer->email
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to send email', [
                    'freelancer_id' => $freelancer->id,
                    'freelancer_email' => $freelancer->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    Log::info('Job notifications completed', [
        'job_id' => $job->id,
        'total_freelancers' => $freelancers->count(),
        'notified_count' => $notifiedCount
    ]);
}
    /**
     * Get freelancers with matching skills for a job.
     *
     * @param \App\Models\Job $job
     * @return \Illuminate\Support\Collection
     */
    public function getMatchingFreelancers(Job $job)
    {
        if (!$job->skills || $job->skills->isEmpty()) {
            return collect();
        }

        return User::where('role', 'freelancer')
            ->whereHas('skills', function ($query) use ($job) {
                $query->whereIn('skill_id', $job->skills->pluck('id')->toArray());
            })
            ->with('skills')
            ->get();
    }
}