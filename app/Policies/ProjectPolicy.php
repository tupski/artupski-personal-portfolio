<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function update(User $user, Project $project): bool
    {
        if ($user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}
