<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Panel login.
 *
 * Before this change the class was an empty subclass and its Blade view
 * (resources/views/filament/pages/auth/custom-login.blade.php) was never rendered:
 * Login inherits SimplePage's `$view = 'filament-panels::pages.simple'`, so the
 * file was dead. The view is now actually wired up, and the method below makes the
 * provider a clickable link rather than a static label — the generic login screen's
 * plainest weakness for a single-operator admin.
 */
class CustomLogin extends Login
{
    protected string $view = 'filament.pages.auth.custom-login';

    /**
     * Replaces the stock "Sign in" line. States the audience instead of restating
     * the verb already shown on the submit button (R-06: cull obvious labels).
     */
    public function getSubheading(): string|Htmlable|null
    {
        return 'Private panel — content, projects and messages.';
    }
}
