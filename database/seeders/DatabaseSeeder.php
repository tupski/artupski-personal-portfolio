<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Order matters: users before authored content, taxonomy before posts, projects before
 * posts (a post may reference a project). Every seeder below is idempotent, so
 * `db:seed` can be re-run on an existing database without duplicating rows.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            NavigationItemSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            TechnologySeeder::class,
            PageSeeder::class,
            ProjectSeeder::class,
            PostSeeder::class,
        ]);
    }
}
