<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * All user-management actions are admin-only. Users still manage their
     * own profile via ProfileController (session-scoped, no policy needed).
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function verify(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function unverify(User $user, User $model): bool
    {
        return $user->is_admin;
    }
}
