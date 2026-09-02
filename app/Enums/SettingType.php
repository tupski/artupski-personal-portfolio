<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Value type of a `site_settings` row — drives the cast and the Filament field (BUILD-PLAN §4).
 */
enum SettingType: string
{
    case String = 'string';
    case Text = 'text';
    case Boolean = 'boolean';
    case Json = 'json';
    case Media = 'media';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
