<?php

namespace App\Filament\RichContent\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class CalloutBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'callout';
    }

    public static function getLabel(): string
    {
        return 'Callout';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->schema([
                Select::make('type')
                    ->label('Callout type')
                    ->options([
                        'info' => 'Info',
                        'warning' => 'Warning',
                        'tip' => 'Tip',
                        'note' => 'Note',
                    ])
                    ->default('info')
                    ->required(),
                Textarea::make('content')
                    ->label('Content')
                    ->required()
                    ->rows(3),
            ]);
    }

    public static function toHtml(array $config, array $data): ?string
    {
        $type = $config['type'] ?? 'info';
        $content = e($config['content'] ?? '');

        $colors = [
            'info' => 'bg-blue-50 dark:bg-blue-900/30 border-blue-400',
            'warning' => 'bg-yellow-50 dark:bg-yellow-900/30 border-yellow-400',
            'tip' => 'bg-green-50 dark:bg-green-900/30 border-green-400',
            'note' => 'bg-gray-50 dark:bg-gray-800 border-gray-400',
        ];

        $icons = [
            'info' => '💡',
            'warning' => '⚠️',
            'tip' => '✅',
            'note' => '📝',
        ];

        $color = $colors[$type] ?? $colors['info'];
        $icon = $icons[$type] ?? $icons['info'];

        return '<div class="my-6 p-4 rounded-lg border-l-4 '.$color.'">'
            .'<div class="flex items-start gap-2">'
            .'<span class="text-lg">'.$icon.'</span>'
            .'<div class="text-sm">'.nl2br($content).'</div>'
            .'</div>'
            .'</div>';
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $type = $config['type'] ?? 'info';
        $icons = ['info' => '💡', 'warning' => '⚠️', 'tip' => '✅', 'note' => '📝'];
        $icon = $icons[$type] ?? '💡';

        return '<div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">'
            .'<span class="text-lg">'.$icon.'</span> '
            .'<span class="text-sm text-gray-600 dark:text-gray-300">'.e($config['content'] ?? '').'</span>'
            .'</div>';
    }
}
