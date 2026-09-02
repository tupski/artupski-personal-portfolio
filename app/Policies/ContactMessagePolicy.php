<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ContactMessage;
use App\Models\User;

class ContactMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ContactMessage $contactMessage): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ContactMessage $contactMessage): bool
    {
        return true;
    }

    public function delete(User $user, ContactMessage $contactMessage): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function forceDelete(User $user, ContactMessage $contactMessage): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}
