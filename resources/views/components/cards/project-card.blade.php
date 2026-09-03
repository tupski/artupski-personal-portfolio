@props([
    'project' => null,
    'href' => null,
])

@php
$projectUrl = $href ?? ($project ? '/projects/' . $project->slug : '#');
$media = $project?->getFirstMedia('gallery') ?? $project?->getFirstMedia('featured');
$technologies = $project?->technologies?->take(3);
$extraCount = $project?->technologies?->count() - 3;
@endphp

<x-ui.card :href="$projectUrl" class="flex flex-col">
    {{-- Media --}}
    <div class="card-media overflow-hidden rounded-t-[var(--radius-lg)]">
        @if($media)
            <img
                src="{{ $media->getUrl('thumb') }}"
                alt="{{ $media->getCustomProperty('alt', $project->title ?? '') }}"
                width="400"
                height="225"
                loading="lazy"
                decoding="async"
                class="w-full aspect-video object-cover transition-transform duration-200 ease-out group-hover:scale-[1.02]"
            >
        @else
            {{-- Empty media: initials block on --bg-muted (§4) --}}
            <div class="w-full aspect-video bg-bg-muted flex items-center justify-center">
                <span class="font-mono text-2xl text-fg-subtle">
                    {{ strtoupper(substr($project->title ?? 'P', 0, 2)) }}
                </span>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex flex-col flex-1 p-4 gap-2">
        <h3 class="text-[var(--text-lg)] font-semibold text-fg leading-snug line-clamp-2 overflow-wrap-anywhere">
            {{ $project->title ?? $slot }}
        </h3>

        @if($project?->short_description)
            <p class="text-sm text-fg-muted line-clamp-3 overflow-wrap-anywhere">
                {{ $project->short_description }}
            </p>
        @endif

        {{-- Tech tags --}}
        @if($technologies && $technologies->count() > 0)
            <div class="mt-auto pt-2 flex flex-wrap gap-1.5">
                @foreach($technologies as $tech)
                    <span class="font-mono text-xs text-fg-subtle">{{ $tech->name }}</span>
                @endforeach
                @if($extraCount > 0)
                    <span class="font-mono text-xs text-fg-subtle">+{{ $extraCount }}</span>
                @endif
            </div>
        @endif

        {{-- Year --}}
        @if($project?->started_on)
            <span class="font-mono text-xs text-fg-subtle mt-1">
                {{ \Carbon\Carbon::parse($project->started_on)->format('Y') }}
                @if($project->ended_on)
                    – {{ \Carbon\Carbon::parse($project->ended_on)->format('Y') }}
                @endif
            </span>
        @endif
    </div>
</x-ui.card>
