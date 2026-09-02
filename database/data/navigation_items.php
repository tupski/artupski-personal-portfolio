<?php

use App\Enums\NavigationLocation;

/**
 * Default navigation (spec §22). Seed data, not code — reorder, hide or add items
 * from Filament without touching this file again.
 *
 * @return array<int, array{label: string, url: string, location: string, sort_order: int, target_blank?: bool, is_visible?: bool}>
 */
return [
    ['label' => 'Home', 'url' => '/', 'location' => NavigationLocation::Header->value, 'sort_order' => 10],
    ['label' => 'About', 'url' => '/about', 'location' => NavigationLocation::Header->value, 'sort_order' => 20],
    ['label' => 'Projects', 'url' => '/projects', 'location' => NavigationLocation::Header->value, 'sort_order' => 30],
    ['label' => 'Blog', 'url' => '/blog', 'location' => NavigationLocation::Header->value, 'sort_order' => 40],
    ['label' => 'Contact', 'url' => '/contact', 'location' => NavigationLocation::Header->value, 'sort_order' => 50],

    ['label' => 'Now', 'url' => '/now', 'location' => NavigationLocation::Footer->value, 'sort_order' => 10],
    ['label' => 'Uses', 'url' => '/uses', 'location' => NavigationLocation::Footer->value, 'sort_order' => 20],
    ['label' => 'Resume', 'url' => '/resume', 'location' => NavigationLocation::Footer->value, 'sort_order' => 30],
    ['label' => 'RSS', 'url' => '/feed', 'location' => NavigationLocation::Footer->value, 'sort_order' => 40],
    [
        'label' => 'GitHub',
        'url' => 'https://github.com/tupski',
        'location' => NavigationLocation::Footer->value,
        'sort_order' => 50,
        'target_blank' => true,
    ],
];
