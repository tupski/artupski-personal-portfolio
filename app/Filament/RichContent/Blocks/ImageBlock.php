<?php

namespace App\Filament\RichContent\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/**
 * Image block.
 *
 * `toHtml()` lands in the public article body, so it now uses the contract's radius
 * token rather than Tailwind's `rounded-lg`, and its caption uses the metadata tokens
 * instead of `gray-500` / `gray-400` (§2.6).
 *
 * `toPreviewHtml()` is admin-only. Its previous version used the 🖼️ emoji as the
 * placeholder graphic; that is an emoji standing in for an icon (antislop R-04), so
 * the preview now names what the block contains instead of decorating an empty box.
 */
class ImageBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'image';
    }

    public static function getLabel(): string
    {
        return 'Image';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->schema([
                TextInput::make('alt')
                    ->label('Alt text')
                    ->required(),
                TextInput::make('caption')
                    ->label('Caption (optional)'),
                Textarea::make('src')
                    ->label('Image URL')
                    ->required()
                    ->rows(1),
            ]);
    }

    public static function toHtml(array $config, array $data): ?string
    {
        $src = e($config['src'] ?? '');
        $alt = e($config['alt'] ?? '');
        $caption = $config['caption'] ?? null;

        if ($src === '') {
            return null;
        }

        // `alt` is required by the editor action, so a content image can never ship
        // without one; an empty alt here would mean a decorative image the editor
        // deliberately left blank.
        $html = '<figure class="my-6">'
            .'<img src="'.$src.'" alt="'.$alt.'" loading="lazy" class="rounded-[var(--radius-lg)] w-full h-auto" />';

        if ($caption) {
            $html .= '<figcaption class="mt-2 text-xs text-[var(--fg-subtle)] text-center">'.e($caption).'</figcaption>';
        }

        return $html.'</figure>';
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $alt = e($config['alt'] ?? '');
        $caption = e($config['caption'] ?? '');

        return '<div class="p-4 bg-gray-100 dark:bg-white/5 rounded-lg text-center">'
            .'<p class="text-sm text-gray-700 dark:text-gray-300">'.($alt !== '' ? $alt : 'No alt text set').'</p>'
            .($caption ? '<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">'.$caption.'</p>' : '')
            .'</div>';
    }
}
