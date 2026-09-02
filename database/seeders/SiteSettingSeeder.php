<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * Default settings for every group in spec §21, from database/data/site_settings.php.
 *
 * Idempotent on (group, key) via firstOrCreate — an existing row keeps whatever the
 * owner set in Filament; only missing keys are added. That makes re-seeding safe on a
 * live database, which `updateOrCreate` would not be.
 */
class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<string, array<string, array{value: ?string, type: string}>> $groups */
        $groups = require database_path('data/site_settings.php');

        foreach ($groups as $group => $settings) {
            foreach ($settings as $key => $setting) {
                SiteSetting::firstOrCreate(
                    [
                        'group' => $group,
                        'key' => $key,
                    ],
                    [
                        'value' => $setting['value'],
                        'type' => $setting['type'],
                    ],
                );
            }
        }
    }
}
