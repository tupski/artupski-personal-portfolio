<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\NavigationItem;
use App\Models\User;

class NavigationItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function view(User $user, NavigationItem $navigationItem): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function update(User $user, NavigationItem $navigationItem): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function delete(User $user, NavigationItem $navigationItem): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function forceDelete(User $user, NavigationItem $navigationItem): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}
