<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Page $page): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function update(User $user, Page $page): bool
    {
        if ($user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Page $page): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function forceDelete(User $user, Page $page): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}
