<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Where a navigation item renders (BUILD-PLAN §4, spec §22).
 */
enum NavigationLocation: string
{
    case Header = 'header';
    case Footer = 'footer';

    public function label(): string
    {
        return match ($this) {
            self::Header => 'Header',
            self::Footer => 'Footer',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
