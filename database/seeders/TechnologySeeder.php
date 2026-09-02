<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

/**
 * Technologies from database/data/technologies.php (spec §43). Idempotent on slug.
 */
class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array{name: string, url: ?string, description: string, sort_order: int}> $technologies */
        $technologies = require database_path('data/technologies.php');

        foreach ($technologies as $technology) {
            Technology::firstOrCreate(
                ['slug' => str($technology['name'])->slug()->value()],
                [
                    'name' => $technology['name'],
                    'url' => $technology['url'],
                    'description' => $technology['description'],
                    'sort_order' => $technology['sort_order'],
                ],
            );
        }
    }
}
