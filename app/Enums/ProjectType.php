<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Project category/type (BUILD-PLAN §4, spec §11).
 */
enum ProjectType: string
{
    case Website = 'website';
    case WebApplication = 'web_application';
    case Cms = 'cms';
    case InternalTool = 'internal_tool';
    case Automation = 'automation';
    case AiTool = 'ai_tool';
    case Saas = 'saas';
    case Government = 'government';
    case Personal = 'personal';
    case OpenSource = 'open_source';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::WebApplication => 'Web application',
            self::Cms => 'CMS',
            self::InternalTool => 'Internal tool',
            self::Automation => 'Automation',
            self::AiTool => 'AI tool',
            self::Saas => 'SaaS',
            self::Government => 'Government project',
            self::Personal => 'Personal project',
            self::OpenSource => 'Open source',
            self::Other => 'Other',
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
