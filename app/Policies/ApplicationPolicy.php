<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Index stays shared among authenticated users.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Application $application): bool
    {
        return $user->is_admin || $user->id === $application->created_by;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Application $application): bool
    {
        return $user->is_admin || $user->id === $application->created_by;
    }

    public function delete(User $user, Application $application): bool
    {
        return $user->is_admin || $user->id === $application->created_by;
    }

    public function restore(User $user, Application $application): bool
    {
        return $user->is_admin || $user->id === $application->created_by;
    }
}
