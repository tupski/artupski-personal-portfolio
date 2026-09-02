<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Categories from database/data/categories.php (spec §17). Idempotent on slug so the
 * admin's later edits to name/description are not clobbered on re-seed — only missing
 * rows are added.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array{name: string, description: string, is_indexable: bool}> $categories */
        $categories = require database_path('data/categories.php');

        foreach ($categories as $index => $category) {
            Category::firstOrCreate(
                ['slug' => str($category['name'])->slug()->value()],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_indexable' => $category['is_indexable'],
                    'sort_order' => ($index + 1) * 10,
                ],
            );
        }
    }
}
