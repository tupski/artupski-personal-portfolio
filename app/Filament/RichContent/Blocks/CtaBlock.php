<?php

namespace App\Filament\RichContent\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\TextInput;

/**
 * Call-to-action block.
 *
 * `toHtml()` lands in the public article body. The previous version rendered
 * `bg-gradient-to-r from-amber-50 to-orange-50`, `rounded-xl` and a `bg-amber-500`
 * button — a gradient fill, a 12px radius above the 10px ceiling and hardcoded
 * non-token colours, each of which the public contract rejects on sight (§1, §2.4,
 * §2.6). It is now a bordered `--bg-subtle` panel with the contract's inverted-neutral
 * primary button, matching the CTA the public Blade block renders.
 *
 * The editor action still requires a button URL, so no dead control can reach the page.
 */
class CtaBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'cta';
    }

    public static function getLabel(): string
    {
        return 'Call to Action';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->schema([
                TextInput::make('heading')
                    ->label('Heading')
                    ->required(),
                TextInput::make('text')
                    ->label('Text (optional)'),
                TextInput::make('button_text')
                    ->label('Button text')
                    ->required(),
                TextInput::make('button_url')
                    ->label('Button URL')
                    ->required(),
            ]);
    }

    public static function toHtml(array $config, array $data): ?string
    {
        $heading = e($config['heading'] ?? '');
        $text = e($config['text'] ?? '');
        $buttonText = e($config['button_text'] ?? '');
        $buttonUrl = e($config['button_url'] ?? '');

        if ($heading === '') {
            return null;
        }

        $html = '<div class="my-8 p-6 sm:p-8 border border-[var(--line)] bg-[var(--bg-subtle)] rounded-[var(--radius-lg)] text-center">'
            .'<h3 class="text-[var(--text-xl)] font-semibold text-[var(--fg)] m-0">'.$heading.'</h3>';

        if ($text !== '') {
            $html .= '<p class="mt-2 text-sm text-[var(--fg-muted)]">'.$text.'</p>';
        }

        if ($buttonText !== '' && $buttonUrl !== '') {
            // Inverted-neutral primary button, contract §2.6 DECISION.
            $html .= '<p class="mt-4 mb-0">'
                .'<a href="'.$buttonUrl.'" class="inline-flex items-center justify-center h-10 px-4 gap-2 text-sm font-medium '
                .'no-underline rounded-[var(--radius-md)] border border-transparent '
                .'bg-[var(--fg)] text-[var(--bg)] hover:bg-[var(--fg-muted)] transition-colors duration-120">'
                .$buttonText
                .'</a></p>';
        }

        return $html.'</div>';
    }

    public static function toPreviewHtml(array $config): ?string
    {
        return '<div class="p-4 bg-gray-100 dark:bg-white/5 rounded-lg text-center">'
            .'<p class="font-semibold text-sm text-gray-900 dark:text-white">'.e($config['heading'] ?? '').'</p>'
            .'<p class="font-mono text-xs text-gray-500 dark:text-gray-400 mt-1">Button: '.e($config['button_text'] ?? '').'</p>'
            .'</div>';
    }
}
