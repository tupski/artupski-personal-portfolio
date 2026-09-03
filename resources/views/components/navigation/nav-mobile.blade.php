@props([
    'items' => collect(),
])

@if($items->count() > 0)
    <div
        x-data
        data-controller="mobile-nav"
        class="md:hidden"
    >
        {{-- Hamburger toggle --}}
        <button
            type="button"
            data-action="click->mobile-nav#toggle"
            data-mobile-nav-target="toggle"
            aria-expanded="false"
            aria-controls="mobile-nav-panel"
            aria-label="Open menu"
            class="relative z-40 flex items-center justify-center w-10 h-10 text-fg hover:text-accent transition-colors duration-120"
        >
            {{-- Hamburger / X icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        {{-- Overlay --}}
        <div
            data-mobile-nav-target="overlay"
            data-action="click->mobile-nav#toggle"
            hidden
            class="fixed inset-0 z-40 bg-black/50"
        ></div>

        {{-- Panel — full-height right sheet (§4) --}}
        <div
            id="mobile-nav-panel"
            data-mobile-nav-target="panel"
            data-nav-panel
            hidden
            class="fixed top-0 right-0 z-50 h-dvh w-72 bg-bg border-l border-line overflow-y-auto overscroll-contain shadow-[var(--shadow-overlay)]"
        >
            <div class="flex items-center justify-between p-4 border-b border-line">
                <span class="font-semibold text-fg">Menu</span>
                <button
                    type="button"
                    data-action="click->mobile-nav#toggle"
                    aria-label="Close menu"
                    class="flex items-center justify-center w-10 h-10 text-fg hover:text-accent transition-colors duration-120"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <nav aria-label="Mobile" class="p-4">
                <ul class="flex flex-col gap-1">
                    @foreach($items as $item)
                        @php
                            $isActive = request()->is(ltrim($item->url, '/') . '*') || request()->is(ltrim($item->url, '/'));
                        @endphp
                        <li>
                            <a
                                href="{{ $item->url }}"
                                @if($item->target_blank) target="_blank" rel="noopener" @endif
                                @if($isActive) aria-current="page" @endif
                                class="block px-3 py-2.5 rounded-[var(--radius-md)] text-sm font-medium transition-colors duration-120
                                    {{ $isActive
                                        ? 'text-accent bg-accent-soft'
                                        : 'text-fg hover:bg-bg-muted'
                                    }}"
                            >{{ $item->label }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
@endif
