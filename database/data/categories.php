<?php

/**
 * Initial blog categories (spec §17). Seed data, not code: these rows are fully
 * manageable in Filament afterwards, and nothing in the app hard-codes them.
 *
 * @return array<int, array{name: string, description: string, is_indexable: bool}>
 */
return [
    [
        'name' => 'Development',
        'description' => 'Hands-on engineering notes from building and shipping real products.',
        'is_indexable' => true,
    ],
    [
        'name' => 'AI',
        'description' => 'Practical uses of language models inside production software.',
        'is_indexable' => true,
    ],
    [
        'name' => 'Laravel',
        'description' => 'Framework patterns, upgrades, and things learned the hard way.',
        'is_indexable' => true,
    ],
    [
        'name' => 'Web Development',
        'description' => 'Frontend, performance, and the modern server-rendered stack.',
        'is_indexable' => true,
    ],
    [
        'name' => 'Automation',
        'description' => 'Removing repetitive work with small, reliable scripts and jobs.',
        'is_indexable' => true,
    ],
    [
        'name' => 'SEO',
        'description' => 'Technical SEO for developers who own their own markup.',
        'is_indexable' => true,
    ],
    [
        'name' => 'Tools',
        'description' => 'The editors, CLIs, and services that make the work faster.',
        'is_indexable' => true,
    ],
    [
        'name' => 'Projects',
        'description' => 'Build logs and post-mortems from client and personal work.',
        'is_indexable' => true,
    ],
    [
        'name' => 'Personal',
        'description' => 'Career, focus, and how the work actually gets done.',
        'is_indexable' => true,
    ],
];
