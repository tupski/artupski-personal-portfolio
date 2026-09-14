@props([
    'project' => null,
    'href' => null,
])

@php
$projectUrl = $href ?? ($project ? '/projects/' . $project->slug : '#');
$media = $project?->getFirstMedia('gallery') ?? $project?->getFirstMedia('featured');
$technologies = $project?->technologies?->take(3);
$extraCount = ($project?->technologies?->count() ?? 0) - 3;
@endphp

{{-- Surface primitive is flux:card. Its own `bg-white` / `border-zinc-200` are written with
     Flux's zero-specificity `[:where(&)]:` prefix, so the token classes below win without
     `!important`; the zinc ramp is remapped to stone in app.css, which is the contract's own
     palette (§2.6). Radius comes from Flux's `rounded-xl`, capped at the 10px ceiling (§2.4). --}}
<flux:card
    class="group relative flex flex-col gap-0 overflow-hidden p-0 bg-bg border-line
           hover:bg-bg-muted hover:border-line-strong
           focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-accent
           transition-colors duration-120"
>
    {{-- Media --}}
    <div class="card-media overflow-hidden">
        @if($media)
            <img
                src="{{ $media->getUrl('thumb') }}"
                alt="{{ $media->getCustomProperty('alt', $project?->title ?? '') }}"
                width="400"
                height="225"
                loading="lazy"
                decoding="async"
                class="w-full aspect-video object-cover transition-transform duration-200 ease-out group-hover:scale-[1.02]"
            >
        @else
            {{-- Empty media: initials block on --bg-muted (§4, §6.1) --}}
            <div class="w-full aspect-video bg-bg-muted flex items-center justify-center">
                <span class="font-mono text-2xl text-fg-subtle">
                    {{ strtoupper(substr($project?->title ?? 'P', 0, 2)) }}
                </span>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex flex-col flex-1 p-4 gap-2">
        <h3 class="text-[var(--text-lg)] font-semibold text-fg leading-snug line-clamp-2 wrap-anywhere">
            {{-- One stretched link covers the card, so the whole surface is clickable while the
                 card itself stays a single focus stop (§9.3). --}}
            <a
                href="{{ $projectUrl }}"
                class="after:absolute after:inset-0 group-hover:text-accent transition-colors duration-120"
            >{{ $project?->title ?? $slot }}</a>
        </h3>

        @if($project?->short_description)
            <p class="text-sm text-fg-muted line-clamp-3 wrap-anywhere">
                {{ $project->short_description }}
            </p>
        @endif

        {{-- Tech tags — max 3 then +N (§4) --}}
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
</flux:card>
