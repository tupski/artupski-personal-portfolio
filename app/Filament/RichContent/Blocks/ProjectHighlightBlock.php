<?php

namespace App\Filament\RichContent\Blocks;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;

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
        $projectId = $config['project_id'] ?? null;

        if (! $projectId) {
            return null;
        }

        $project = Project::find($projectId);

        if (! $project) {
            return null;
        }

        $title = e($project->title);
        $description = e($project->short_description ?? '');
        $url = e('/projects/'.$project->slug);

        return '<div class="my-6 p-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 transition-colors">'
            .'<p class="text-xs uppercase tracking-wide text-amber-600 dark:text-amber-400 font-semibold mb-1">Featured Project</p>'
            .'<h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">'
            .'<a href="'.$url.'" class="hover:text-amber-500 transition-colors">'.$title.'</a>'
            .'</h4>'
            .($description ? '<p class="text-gray-600 dark:text-gray-300 text-sm">'.$description.'</p>' : '')
            .'</div>';
    }

    public static function toPreviewHtml(array $config): ?string
    {
        $projectId = $config['project_id'] ?? null;

        if (! $projectId) {
            return '<div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg text-sm text-gray-500">Project not found</div>';
        }

        $project = Project::find($projectId);

        if (! $project) {
            return '<div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg text-sm text-gray-500">Project not found</div>';
        }

        return '<div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">'
            .'<p class="text-xs text-amber-600 font-semibold">Featured Project</p>'
            .'<p class="font-bold text-sm">'.e($project->title).'</p>'
            .'</div>';
    }
}
