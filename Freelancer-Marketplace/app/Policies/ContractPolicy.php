<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contract;

class ContractPolicy
{
    /**
     * Determine if a user can view a contract.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contract  $contract
     * @return bool
     */
    public function view(User $user, Contract $contract): bool
    {
        // Admin can view any contract
        if ($user->isAdmin()) {
            return true;
        }

        // Client can view their contracts
        if ($user->isClient() && $user->id === $contract->client_id) {
            return true;
        }

        // Freelancer can view their contracts
        if ($user->isFreelancer() && $user->id === $contract->freelancer_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a user can update a contract.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contract  $contract
     * @return bool
     */
    public function update(User $user, Contract $contract): bool
    {
        // Admin can update any contract
        if ($user->isAdmin()) {
            return true;
        }

        // Client can update their contracts
        return $user->isClient() && $user->id === $contract->client_id;
    }

    /**
     * Determine if a user can view any contracts.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view their contracts
        return true;
    }
}