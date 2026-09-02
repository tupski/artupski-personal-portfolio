<?php

use App\Enums\SettingType;

/**
 * Default site settings, one row per key, grouped per spec §21. Seed data, not code.
 * Every group in §21 is represented so the settings page has no empty section.
 *
 * `media` rows hold a medialibrary UUID once an image is uploaded; they seed empty.
 *
 * @return array<string, array<string, array{value: ?string, type: string}>>
 */
return [

    'identity' => [
        'site_name' => ['value' => 'Artupski', 'type' => SettingType::String->value],
        'site_title' => ['value' => 'Angga Tupski — Web Developer', 'type' => SettingType::String->value],
        'site_description' => [
            'value' => 'Web developer building fast, server-rendered Laravel applications, content systems and automation.',
            'type' => SettingType::Text->value,
        ],
        'logo' => ['value' => null, 'type' => SettingType::Media->value],
        'favicon' => ['value' => null, 'type' => SettingType::Media->value],
    ],

    'personal' => [
        'name' => ['value' => 'Angga Tupski', 'type' => SettingType::String->value],
        'headline' => ['value' => 'I build web applications that stay fast as they grow.', 'type' => SettingType::String->value],
        'bio' => [
            'value' => 'Web developer focused on Laravel, content systems and automation. I work with small teams who need software that ships and keeps working after launch.',
            'type' => SettingType::Text->value,
        ],
        'profile_photo' => ['value' => null, 'type' => SettingType::Media->value],
        'location' => ['value' => 'Indonesia', 'type' => SettingType::String->value],
    ],

    'contact' => [
        'email' => ['value' => 'hello@artupski.com', 'type' => SettingType::String->value],
        'phone' => ['value' => null, 'type' => SettingType::String->value],
        'contact_url' => ['value' => '/contact', 'type' => SettingType::String->value],
    ],

    'social' => [
        'github' => ['value' => 'https://github.com/tupski', 'type' => SettingType::String->value],
        'linkedin' => ['value' => null, 'type' => SettingType::String->value],
        'instagram' => ['value' => null, 'type' => SettingType::String->value],
        'x' => ['value' => null, 'type' => SettingType::String->value],
        'youtube' => ['value' => null, 'type' => SettingType::String->value],
        'threads' => ['value' => null, 'type' => SettingType::String->value],
    ],

    'seo' => [
        'default_title' => ['value' => 'Angga Tupski — Web Developer', 'type' => SettingType::String->value],
        'default_description' => [
            'value' => 'Projects, articles and notes on building maintainable web software with Laravel.',
            'type' => SettingType::Text->value,
        ],
        'default_og_image' => ['value' => null, 'type' => SettingType::Media->value],
        'robots_enabled' => ['value' => '1', 'type' => SettingType::Boolean->value],
    ],

    'analytics' => [
        'provider' => ['value' => null, 'type' => SettingType::String->value],
        'google_analytics_id' => ['value' => null, 'type' => SettingType::String->value],
        'plausible_domain' => ['value' => null, 'type' => SettingType::String->value],
        'umami_website_id' => ['value' => null, 'type' => SettingType::String->value],
        'umami_script_url' => ['value' => null, 'type' => SettingType::String->value],
        'custom_head_snippet' => ['value' => null, 'type' => SettingType::Text->value],
    ],

];
