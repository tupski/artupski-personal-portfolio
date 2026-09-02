<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Delivery/work status of a project, distinct from its publishing status (BUILD-PLAN §4).
 */
enum ProjectStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Maintained = 'maintained';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::InProgress => 'In progress',
            self::Completed => 'Completed',
            self::Maintained => 'Maintained',
            self::Archived => 'Archived',
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
