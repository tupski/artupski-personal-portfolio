<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

/**
 * Tags from database/data/tags.php (spec §18). Idempotent on slug.
 */
class TagSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array{name: string, is_indexable: bool}> $tags */
        $tags = require database_path('data/tags.php');

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => str($tag['name'])->slug()->value()],
                [
                    'name' => $tag['name'],
                    'is_indexable' => $tag['is_indexable'],
                ],
            );
        }
    }
}
