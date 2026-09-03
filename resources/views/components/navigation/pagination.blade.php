@props([
    'paginator' => null,
])

@if($paginator && $paginator->hasPages())
    <nav aria-label="Pagination" class="mt-8">
        <ul class="flex items-center justify-center gap-1 font-mono text-sm">
            {{-- Previous --}}
            @if($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 text-fg-subtle cursor-default" aria-disabled="true">Prev</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-fg hover:text-accent hover:bg-bg-muted rounded-[var(--radius-md)] transition-colors duration-120">Prev</a>
                </li>
            @endif

            {{-- Page numbers — windowed (§4) --}}
            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                <li>
                    @if($page == $paginator->currentPage())
                        <span aria-current="page" class="px-3 py-2 bg-bg-muted text-fg rounded-[var(--radius-md)] font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-fg hover:text-accent hover:bg-bg-muted rounded-[var(--radius-md)] transition-colors duration-120">{{ $page }}</a>
                    @endif
                </li>
            @endforeach

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-fg hover:text-accent hover:bg-bg-muted rounded-[var(--radius-md)] transition-colors duration-120">Next</a>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 text-fg-subtle cursor-default" aria-disabled="true">Next</span>
                </li>
            @endif
        </ul>

        {{-- Page info --}}
        <p class="mt-2 text-center text-xs text-fg-subtle font-mono">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </p>
    </nav>
@endif
