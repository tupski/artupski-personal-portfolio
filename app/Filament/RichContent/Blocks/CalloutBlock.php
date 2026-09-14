<?php

namespace App\Filament\RichContent\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;

/**
 * Callout block.
 *
 * `toHtml()` output is injected into the public article body, so it has to obey the
 * public design contract, not Filament's admin palette. The previous version used
 * Tailwind's `blue-50` / `yellow-50` / `green-50` / `gray-50` plus emoji icons
 * (💡 ⚠️ ✅ 📝), which broke three rules at once: hardcoded non-token colours (§2.6),
 * and emoji standing in for icons (antislop R-04). Colour is now paired with an inline
 * SVG so state never rests on colour alone (§9.7) and a reader with no emoji font
 * still gets the meaning.
 *
 * `toPreviewHtml()` is admin-only, so it keeps Filament's neutral tokens.
 */
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

        // Contract §2.6 tokens only. Each entry is: border/icon/text in both themes.
        $variants = [
            'info' => [
                'fg' => 'var(--fg-muted)',
                'border' => 'var(--line)',
                'bg' => 'var(--bg-subtle)',
                'icon' => 'M10 2a8 8 0 100 16 8 8 0 000-16zm0 3.5a1 1 0 110 2 1 1 0 010-2zm0 3.5a.75.75 0 01.75.75v4a.75.75 0 01-1.5 0v-4A.75.75 0 0110 9z',
            ],
            'note' => [
                'fg' => 'var(--fg-muted)',
                'border' => 'var(--line)',
                'bg' => 'var(--bg-subtle)',
                'icon' => 'M10 2a8 8 0 100 16 8 8 0 000-16zm0 3.5a1 1 0 110 2 1 1 0 010-2zm0 3.5a.75.75 0 01.75.75v4a.75.75 0 01-1.5 0v-4A.75.75 0 0110 9z',
            ],
            'warning' => [
                'fg' => 'var(--warn)',
                'border' => 'var(--warn)',
                'bg' => 'var(--warn-soft)',
                'icon' => 'M8.9 2.6a1.25 1.25 0 012.2 0l6.3 11.2A1.25 1.25 0 0116.3 16H3.7a1.25 1.25 0 01-1.1-2.2L8.9 2.6zM10 7a.75.75 0 00-.75.75v3a.75.75 0 001.5 0v-3A.75.75 0 0010 7zm0 6.5a.9.9 0 100 1.8.9.9 0 000-1.8z',
            ],
            'tip' => [
                'fg' => 'var(--success)',
                'border' => 'var(--success)',
                'bg' => 'var(--success-soft)',
                'icon' => 'M10 2a8 8 0 100 16 8 8 0 000-16zm3.8 6.03a.75.75 0 00-1.1-1.02l-3.2 3.45-1.4-1.35a.75.75 0 10-1.04 1.08l2 1.93a.75.75 0 001.06-.03l3.68-3.96z',
            ],
        ];

        $v = $variants[$type] ?? $variants['info'];

        return '<div class="my-6 p-4 rounded-[var(--radius-lg)] border" style="border-color:'.$v['border']
            .';background-color:'.$v['bg'].';color:'.$v['fg'].'">'
            .'<div class="flex items-start gap-3">'
            .'<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="mt-0.5 shrink-0" width="18" height="18" style="color:'.$v['fg'].'">'
            .'<path d="'.$v['icon'].'"/></svg>'
            .'<div class="text-sm leading-relaxed">'.nl2br($content).'</div>'
            .'</div>'
            .'</div>';
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $type = $config['type'] ?? 'info';

        return '<div class="p-3 bg-gray-100 dark:bg-white/5 rounded-lg">'
            .'<span class="font-mono text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">'.e($type).'</span> '
            .'<span class="text-sm text-gray-700 dark:text-gray-300">'.e(Str::limit($config['content'] ?? '', 80)).'</span>'
            .'</div>';
    }
}
