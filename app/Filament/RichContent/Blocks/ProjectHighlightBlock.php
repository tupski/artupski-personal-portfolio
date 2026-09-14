<?php

namespace App\Filament\RichContent\Blocks;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;

/**
 * Project highlight block.
 *
 * `toHtml()` output is injected into the public article body. The previous version
 * used `rounded-xl` (12px, above the contract's 10px ceiling, §2.4) and hardcoded
 * `gray-*` / `amber-*` values instead of tokens (§2.6). Both panels now read from the
 * same tokens the public Blade block uses, so the editor's output and the Blade
 * component render identically — which was the point of having both.
 */
class ProjectHighlightBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'project-highlight';
    }

    public static function getLabel(): string
    {
        return 'Project Highlight';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->schema([
                Select::make('project_id')
                    ->label('Project')
                    ->options(fn () => Project::pluck('title', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function toHtml(array $config, array $data): ?string
    {
        $project = self::resolveProject($config['project_id'] ?? null);

        if (! $project) {
            return null;
        }

        $title = e($project->title);
        $description = e($project->short_description ?? '');
        $url = e('/projects/'.$project->slug);

        return '<div class="my-8 border border-[var(--line)] rounded-[var(--radius-lg)] overflow-hidden">'
            .'<div class="p-4 sm:p-6">'
            .'<p class="font-mono text-xs uppercase tracking-wider text-[var(--fg-subtle)] mb-1">Featured Project</p>'
            .'<h4 class="text-[var(--text-lg)] font-semibold text-[var(--fg)] m-0">'
            .'<a href="'.$url.'" class="no-underline text-[var(--accent)] hover:text-[var(--accent-strong)] transition-colors duration-120">'.$title.'</a>'
            .'</h4>'
            .($description !== '' ? '<p class="mt-1 text-sm text-[var(--fg-muted)]">'.$description.'</p>' : '')
            .'</div>'
            .'</div>';
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $project = self::resolveProject($config['project_id'] ?? null);

        if (! $project) {
            // Empty state names the cause rather than showing a bare placeholder (R-27).
            return '<div class="p-3 bg-gray-100 dark:bg-white/5 rounded-lg text-sm text-gray-500 dark:text-gray-400">'
                .'Pick a project to show it here.</div>';
        }

        return '<div class="p-3 bg-gray-100 dark:bg-white/5 rounded-lg">'
            .'<p class="font-mono text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">Featured project</p>'
            .'<p class="font-semibold text-sm text-gray-900 dark:text-white">'.e($project->title).'</p>'
            .'</div>';
    }

    protected static function resolveProject(mixed $projectId): ?Project
    {
        return $projectId ? Project::find($projectId) : null;
    }
}
