<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Job;

class JobPolicy
{
    /**
     * Determine if a user can create a job.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isClient() || $user->isAdmin();
    }

    /**
     * Determine if a user can update a job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return bool
     */
    public function update(User $user, Job $job): bool
    {
        // Admin can update any job
        if ($user->isAdmin()) {
            return true;
        }

        // Client can only update their own open jobs
        return $user->isClient() && 
               $user->id === $job->client_id && 
               $job->status === 'open';
    }

    /**
     * Determine if a user can delete a job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return bool
     */
    public function delete(User $user, Job $job): bool
    {
        // Admin can delete any job
        if ($user->isAdmin()) {
            return true;
        }

        // Client can only delete their own open jobs
        return $user->isClient() && 
               $user->id === $job->client_id && 
               $job->status === 'open';
    }

    /**
     * Determine if a user can view any jobs.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view jobs
    }

    /**
     * Determine if a user can view a specific job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return bool
     */
    public function view(User $user, Job $job): bool
    {
        return true; // Everyone can view job details
    }
}