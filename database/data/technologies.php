<?php

/**
 * Initial technology list (spec §43). Seed data, not code.
 *
 * @return array<int, array{name: string, url: ?string, description: string, sort_order: int}>
 */
return [
    [
        'name' => 'Laravel',
        'url' => 'https://laravel.com',
        'description' => 'The framework behind most of what I ship.',
        'sort_order' => 10,
    ],
    [
        'name' => 'PHP',
        'url' => 'https://www.php.net',
        'description' => 'Primary language, 8.3+ with strict types where it earns its keep.',
        'sort_order' => 20,
    ],
    [
        'name' => 'Filament',
        'url' => 'https://filamentphp.com',
        'description' => 'Admin panels clients actually enjoy using.',
        'sort_order' => 30,
    ],
    [
        'name' => 'Tailwind CSS',
        'url' => 'https://tailwindcss.com',
        'description' => 'Utility-first styling with a small design system layered on top.',
        'sort_order' => 40,
    ],
    [
        'name' => 'Turbo',
        'url' => 'https://turbo.hotwired.dev',
        'description' => 'Fast navigation without giving up server-rendered HTML.',
        'sort_order' => 50,
    ],
    [
        'name' => 'Stimulus',
        'url' => 'https://stimulus.hotwired.dev',
        'description' => 'Behaviour sprinkles for the few places HTML is not enough.',
        'sort_order' => 60,
    ],
    [
        'name' => 'MySQL',
        'url' => 'https://www.mysql.com',
        'description' => 'Default relational store; MariaDB in local development.',
        'sort_order' => 70,
    ],
    [
        'name' => 'Vite',
        'url' => 'https://vite.dev',
        'description' => 'Asset pipeline for every frontend build.',
        'sort_order' => 80,
    ],
    [
        'name' => 'Docker',
        'url' => 'https://www.docker.com',
        'description' => 'Reproducible environments from laptop to server.',
        'sort_order' => 90,
    ],
    [
        'name' => 'OpenAI API',
        'url' => 'https://platform.openai.com',
        'description' => 'LLM features kept behind server-side endpoints.',
        'sort_order' => 100,
    ],
    [
        'name' => 'Supabase',
        'url' => 'https://supabase.com',
        'description' => 'Postgres plus auth when a project starts serverless.',
        'sort_order' => 110,
    ],
    [
        'name' => 'WordPress',
        'url' => 'https://wordpress.org',
        'description' => 'Legacy platform I still migrate clients away from.',
        'sort_order' => 120,
    ],
];
