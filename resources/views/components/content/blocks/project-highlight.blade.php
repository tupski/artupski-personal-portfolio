@props([
    'data' => [],
])

@php
$projectId = $data['project_id'] ?? null;
$project = $projectId ? \App\Models\Project::find($projectId) : null;
@endphp

@if($project)
    <div class="my-8 border border-line rounded-[var(--radius-lg)] overflow-hidden">
        <div class="p-4 sm:p-6">
            <p class="font-mono text-xs text-fg-subtle uppercase tracking-wider mb-1">Featured Project</p>
            <h4 class="text-[var(--text-lg)] font-semibold text-fg">
                <a href="/projects/{{ $project->slug }}" class="hover:text-accent transition-colors duration-120">
                    {{ $project->title }}
                </a>
            </h4>
            @if($project->short_description)
                <p class="mt-1 text-sm text-fg-muted">{{ $project->short_description }}</p>
            @endif
            <div class="mt-3">
                <flux:button href="/projects/{{ $project->slug }}" variant="outline" size="sm">
                    View project
                </flux:button>
            </div>
        </div>
    </div>
@endif
