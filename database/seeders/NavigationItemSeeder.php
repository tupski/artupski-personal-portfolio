<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

/**
 * Default header/footer navigation from database/data/navigation_items.php (spec §22).
 * Idempotent on (location, label).
 */
class NavigationItemSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = require database_path('data/navigation_items.php');

        foreach ($items as $item) {
            NavigationItem::firstOrCreate(
                [
                    'location' => $item['location'],
                    'label' => $item['label'],
                ],
                [
                    'url' => $item['url'],
                    'sort_order' => $item['sort_order'],
                    'target_blank' => $item['target_blank'] ?? false,
                    'is_visible' => $item['is_visible'] ?? true,
                ],
            );
        }
    }
}
