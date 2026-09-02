<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Redirect;
use App\Models\User;

class RedirectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function view(User $user, Redirect $redirect): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function update(User $user, Redirect $redirect): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function delete(User $user, Redirect $redirect): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function forceDelete(User $user, Redirect $redirect): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}
