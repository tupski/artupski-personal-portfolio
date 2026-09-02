<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Admin roles (BUILD-PLAN §4, spec §34).
 */
enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Editor = 'editor';
    case Author = 'author';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super admin',
            self::Editor => 'Editor',
            self::Author => 'Author',
        };
    }

    /**
     * Roles allowed to publish or delete content (spec §34).
     */
    public function canPublish(): bool
    {
        return $this === self::SuperAdmin || $this === self::Editor;
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
