<?php

namespace App\Policies;

use App\Models\{User, JobListing};

class JobListingPolicy
{
    public function view(?User $user, JobListing $job): bool
    {
        return $job->canView($user);
    }

    public function apply(User $user, JobListing $job): bool
    {
        return $user->hasVerifiedEmail() && !$job->hasApplied($user);
    }
}
