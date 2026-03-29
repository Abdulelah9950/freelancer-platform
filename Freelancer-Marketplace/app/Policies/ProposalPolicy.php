<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Proposal;
use App\Models\Job;

class ProposalPolicy
{
    /**
     * Determine if a user can create a proposal for a job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return bool
     */
    public function create(User $user, Job $job): bool
    {
        // Check if user is a freelancer
        if (!$user->isFreelancer()) {
            return false;
        }

        // Check if job is open
        if ($job->status !== 'open') {
            return false;
        }

        // Check if user already submitted a proposal for this job
        $existingProposal = Proposal::where('job_id', $job->id)
            ->where('freelancer_id', $user->id)
            ->exists();

        return !$existingProposal;
    }

    /**
     * Determine if a user can view a proposal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return bool
     */
    public function view(User $user, Proposal $proposal): bool
    {
        // Admin can view any proposal
        if ($user->isAdmin()) {
            return true;
        }

        // Freelancer can view their own proposals
        if ($user->isFreelancer() && $user->id === $proposal->freelancer_id) {
            return true;
        }

        // Client can view proposals for their jobs
        if ($user->isClient() && $user->id === $proposal->job->client_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a user can update a proposal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return bool
     */
    public function update(User $user, Proposal $proposal): bool
    {
        // Only the freelancer who created the proposal can update it
        return $user->isFreelancer() && 
               $user->id === $proposal->freelancer_id && 
               $proposal->status === 'pending' &&
               $proposal->job->status === 'open';
    }

    /**
     * Determine if a user can delete a proposal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return bool
     */
    public function delete(User $user, Proposal $proposal): bool
    {
        // Only the freelancer who created the proposal can delete it
        return $user->isFreelancer() && 
               $user->id === $proposal->freelancer_id && 
               $proposal->status === 'pending';
    }

    /**
     * Determine if a user can accept a proposal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return bool
     */
    public function accept(User $user, Proposal $proposal): bool
    {
        return $user->isClient() && 
               $user->id === $proposal->job->client_id && 
               $proposal->status === 'pending' &&
               $proposal->job->status === 'open';
    }

    /**
     * Determine if a user can reject a proposal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return bool
     */
    public function reject(User $user, Proposal $proposal): bool
    {
        return $user->isClient() && 
               $user->id === $proposal->job->client_id && 
               $proposal->status === 'pending';
    }
}