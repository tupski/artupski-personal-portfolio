<?php

/**
 * Initial tags (spec §18). Seed data, not code. `is_indexable` stays false so tag
 * pages are excluded from the sitemap until one is deliberately promoted (§57).
 *
 * @return array<int, array{name: string, is_indexable: bool}>
 */
return [
    ['name' => 'Laravel', 'is_indexable' => true],
    ['name' => 'PHP', 'is_indexable' => true],
    ['name' => 'JavaScript', 'is_indexable' => false],
    ['name' => 'Turbo', 'is_indexable' => false],
    ['name' => 'Hotwire', 'is_indexable' => false],
    ['name' => 'Filament', 'is_indexable' => false],
    ['name' => 'AI', 'is_indexable' => false],
    ['name' => 'OpenAI', 'is_indexable' => false],
    ['name' => 'Supabase', 'is_indexable' => false],
    ['name' => 'Vercel', 'is_indexable' => false],
    ['name' => 'SEO', 'is_indexable' => false],
    ['name' => 'Automation', 'is_indexable' => false],
    ['name' => 'WordPress', 'is_indexable' => false],
];
