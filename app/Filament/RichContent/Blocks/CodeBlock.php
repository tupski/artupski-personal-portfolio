<?php

namespace App\Filament\RichContent\Blocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CodeBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'code';
    }

    public static function getLabel(): string
    {
        return 'Code Block';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->schema([
                TextInput::make('title')
                    ->label('Title (optional)')
                    ->placeholder('e.g. Example.php'),
                Select::make('language')
                    ->label('Language')
                    ->options([
                        'php' => 'PHP',
                        'javascript' => 'JavaScript',
                        'html' => 'HTML',
                        'css' => 'CSS',
                        'bash' => 'Bash',
                        'json' => 'JSON',
                        'python' => 'Python',
                        'sql' => 'SQL',
                        'yaml' => 'YAML',
                    ])
                    ->default('php'),
                Textarea::make('code')
                    ->label('Code')
                    ->required()
                    ->rows(8)
                    ->fontFamily('mono'),
            ]);
    }

    public static function toHtml(array $config, array $data): ?string
    {
        $code = e($config['code'] ?? '');
        $title = $config['title'] ?? null;
        $language = e($config['language'] ?? 'php');

        $html = '<div class="my-6 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">';

        if ($title) {
            $html .= '<div class="bg-gray-100 dark:bg-gray-800 px-4 py-2 text-xs font-mono text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">'
                .e($title)
                .'</div>';
        }

        $html .= '<pre class="bg-gray-50 dark:bg-gray-900 p-4 overflow-x-auto text-sm"><code class="language-'.$language.'">'
            .$code
            .'</code></pre>'
            .'</div>';

        return $html;
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $title = e($config['title'] ?? 'Code');
        $language = e($config['language'] ?? '');

        return '<div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg font-mono text-xs">'
            .'<p class="text-gray-500 mb-1">'.$title.($language ? " ({$language})" : '').'</p>'
            .'<pre class="text-gray-700 dark:text-gray-300 overflow-hidden">'.e(substr($config['code'] ?? '', 0, 60)).'…</pre>'
            .'</div>';
    }
}
