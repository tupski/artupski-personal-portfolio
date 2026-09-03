<?php

namespace App\Providers;

use App\Enums\NavigationLocation;
use App\Models\NavigationItem;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share navigation and site identity with all layouts (§3.5)
        View::composer('components.layouts.app', function ($view) {
            $headerItems = NavigationItem::query()
                ->visible()
                ->location(NavigationLocation::Header)
                ->ordered()
                ->get();

            $footerItems = NavigationItem::query()
                ->visible()
                ->location(NavigationLocation::Footer)
                ->ordered()
                ->get();

            // Fetch site settings — identity + personal + social groups
            $settings = SiteSetting::query()
                ->whereIn('group', ['identity', 'personal', 'social'])
                ->get()
                ->mapWithKeys(fn ($s) => [$s->key => $s->typedValue()]);

            $socialLinks = collect([
                'GitHub' => $settings['github'] ?? null,
                'LinkedIn' => $settings['linkedin'] ?? null,
                'Instagram' => $settings['instagram'] ?? null,
            ])->filter()->toArray();

            $view->with([
                'headerNavItems' => $headerItems,
                'footerNavItems' => $footerItems,
                'siteName' => $settings['site_name'] ?? config('app.name'),
                'siteHeadline' => $settings['headline'] ?? '',
                'socialLinks' => $socialLinks,
            ]);
        });
    }
}
