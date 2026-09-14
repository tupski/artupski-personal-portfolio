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

        // Token-based so the editor's output matches resources/views/components/content/code-block.blade.php
        // (§2.6). Note: no copy-button markup here — that is Stimulus-driven in the Blade
        // component, and the editor's HTML is sanitized on save, so injecting a scripted
        // control into article HTML would not survive and would be a dead control (R-26).
        $html = '<div class="my-6 rounded-[var(--radius-lg)] overflow-hidden border border-[var(--line)]">';

        if ($title) {
            $html .= '<div class="bg-[var(--bg-muted)] px-4 py-2 font-mono text-xs text-[var(--fg-subtle)] border-b border-[var(--line)]">'
                .e($title)
                .'</div>';
        }

        $html .= '<pre class="bg-[var(--bg-subtle)] p-4 overflow-x-auto text-sm m-0"><code class="language-'.$language.' font-mono">'
            .$code
            .'</code></pre>'
            .'</div>';

        return $html;
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $title = e($config['title'] ?? 'Code');
        $language = e($config['language'] ?? '');

        return '<div class="p-3 bg-gray-100 dark:bg-white/5 rounded-lg font-mono text-xs">'
            .'<p class="text-gray-500 dark:text-gray-400 mb-1">'.$title.($language ? " ({$language})" : '').'</p>'
            .'<pre class="text-gray-700 dark:text-gray-300 overflow-hidden">'.e(substr($config['code'] ?? '', 0, 60)).'</pre>'
            .'</div>';
    }
}
