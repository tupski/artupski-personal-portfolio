<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Widgets\ContentStatsOverview;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\UnreadMessagesWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('tupasadmin')
            ->path('tupasadmin')
            ->login(CustomLogin::class)
            ->registration(false)
            ->passwordReset(false)
            ->emailVerification(false)
            ->darkMode(true)
            ->brandName('Artupski')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                /* Retained so Filament's own palettes know the active hue; the actual
                   ember ramp is defined in resources/css/filament/admin/theme.css. */
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                ContentStatsOverview::class,
                UnreadMessagesWidget::class,
                RecentActivityWidget::class,
            ])
            /* Flux Pro runtime, admin only.
               Pro's interactive components (modal, dropdown, tabs, accordion, command palette,
               date picker, editor, file upload, charts) need Flux's JS. It is loaded here — on
               the panel, never on the public site — through Filament's own render-hook API
               rather than by editing vendor markup or overriding the panel layout.

               `@fluxAppearance` is deliberately NOT included. It would write its own
               `localStorage['flux.appearance']` and toggle `.dark` on <html>, competing with
               Filament's theme switcher, which uses `localStorage['theme']`. Flux's JS treats a
               missing appearance API as a supported case (it falls back to a no-op), so Flux
               components simply follow the `.dark` class Filament already manages — one theme
               owner instead of two. */
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('@fluxScripts'),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
