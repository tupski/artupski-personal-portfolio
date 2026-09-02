<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\SiteSetting;
use App\Models\User;

class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function view(User $user, SiteSetting $siteSetting): bool
    {
        return $user->role === UserRole::SuperAdmin || $user->role === UserRole::Editor;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, SiteSetting $siteSetting): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function delete(User $user, SiteSetting $siteSetting): bool
    {
        return false;
    }

    public function forceDelete(User $user, SiteSetting $siteSetting): bool
    {
        return false;
    }
}
