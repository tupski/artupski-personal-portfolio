<?php

namespace App\Filament\RichContent\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\TextInput;

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
        $buttonText = e($config['button_text'] ?? 'Learn more');
        $buttonUrl = e($config['button_url'] ?? '#');

        return '<div class="my-8 p-8 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl text-center border border-amber-200 dark:border-amber-800">'
            .'<h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">'.$heading.'</h3>'
            .($text ? '<p class="text-gray-600 dark:text-gray-300 mb-4">'.$text.'</p>' : '')
            .'<a href="'.$buttonUrl.'" class="inline-block px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">'
            .$buttonText
            .'</a>'
            .'</div>';
    }

    public static function toPreviewHtml(array $config): ?string
    {
        return '<div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg text-center border border-amber-200 dark:border-amber-800">'
            .'<p class="font-bold text-sm">'.e($config['heading'] ?? '').'</p>'
            .'<p class="text-xs text-gray-500 mt-1">Button: '.e($config['button_text'] ?? '').'</p>'
            .'</div>';
    }
}
