@props([
    'code' => '',
    'language' => null,
    'title' => null,
])

<div
    data-code-block
    class="relative rounded-[var(--radius-lg)] border border-line bg-bg-subtle my-6"
>
    {{-- Language label + title (§4) --}}
    @if($title || $language)
        <div class="flex items-center justify-between px-4 pt-3 pb-0">
            <span class="font-mono text-[11px] uppercase tracking-wider text-fg-subtle">
                {{ $title ?? $language }}
            </span>
        </div>
    @endif

    {{-- Code with horizontal scroll (§4) --}}
    <div
        tabindex="0"
        role="region"
        aria-label="Code block{{ $language ? ' (' . $language . ')' : '' }}"
        class="overflow-x-auto -webkit-overflow-scrolling-touch"
    >
        <pre class="p-4 {{ $title || $language ? 'pt-2' : '' }} text-sm leading-relaxed font-mono text-fg whitespace-pre tab-size-2"><code>{{ $code }}</code></pre>
    </div>

    {{-- Copy button — progressive enhancement (§4, §72) --}}
    <div
        data-controller="copy"
        class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-120 print:hidden"
    >
        <button
            type="button"
            data-action="click->copy#copy"
            class="flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-md)] bg-bg-muted text-fg-subtle hover:text-fg text-xs font-mono transition-colors duration-120"
            aria-label="Copy code to clipboard"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
            <span data-copy-target="label">Copy</span>
        </button>
    </div>
</div>
