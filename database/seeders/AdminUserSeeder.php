<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * The single owner account (spec §106). Idempotent: keyed on email.
 *
 * Password comes from `SEED_ADMIN_PASSWORD` when set. The fallback is a local-dev
 * convenience only — production credentials are rotated with `artisan`, never seeded.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SEED_ADMIN_EMAIL', 'angga@artupski.com');
        $password = env('SEED_ADMIN_PASSWORD', 'password');

        $user = User::firstOrNew(['email' => $email]);

        $user->name = env('SEED_ADMIN_NAME', 'Angga Tupski');
        $user->role = UserRole::SuperAdmin;
        $user->email_verified_at ??= now();

        if (! $user->exists) {
            $user->password = Hash::make($password);
        }

        $user->save();
    }
}
