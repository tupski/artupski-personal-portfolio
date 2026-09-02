<?php

namespace App\Filament\RichContent\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

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

        $html = '<figure class="my-6">';
        $html .= '<img src="'.$src.'" alt="'.$alt.'" loading="lazy" class="rounded-lg w-full" />';

        if ($caption) {
            $html .= '<figcaption class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">'.e($caption).'</figcaption>';
        }

        $html .= '</figure>';

        return $html;
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $alt = e($config['alt'] ?? 'Image');
        $caption = e($config['caption'] ?? '');

        return '<div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg text-center">'
            .'<div class="text-gray-400 text-3xl mb-2">🖼️</div>'
            .'<p class="text-sm text-gray-600 dark:text-gray-300">'.$alt.'</p>'
            .($caption ? '<p class="text-xs text-gray-400 mt-1">'.$caption.'</p>' : '')
            .'</div>';
    }
}
