<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Technology;
use App\Models\User;

class TechnologyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Technology $technology): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function update(User $user, Technology $technology): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function delete(User $user, Technology $technology): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function forceDelete(User $user, Technology $technology): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}
