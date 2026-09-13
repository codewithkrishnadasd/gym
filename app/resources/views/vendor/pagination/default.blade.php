@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination"
        class="flex flex-wrap items-center justify-between gap-3 border-t border-hairline px-4 py-3">
        <p class="text-xs text-ink-muted">
            Showing <span class="numeric font-medium text-ink-soft">{{ $paginator->firstItem() }}</span>
            to <span class="numeric font-medium text-ink-soft">{{ $paginator->lastItem() }}</span>
            of <span class="numeric font-medium text-ink-soft">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="grid h-9 w-9 place-items-center rounded-lg text-ink-muted opacity-40" aria-disabled="true">
                    <x-heroicon-o-chevron-left class="h-4 w-4" />
                </span>
            @else
                <button type="button" wire:click="previousPage" wire:loading.attr="disabled" rel="prev" aria-label="Previous page"
                    class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
                    <x-heroicon-o-chevron-left class="h-4 w-4" />
                </button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-1.5 text-xs text-ink-muted">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="numeric grid h-9 min-w-9 place-items-center rounded-lg bg-accent px-2 text-[13px] font-semibold text-on-accent">{{ $page }}</span>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                                class="numeric grid h-9 min-w-9 place-items-center rounded-lg px-2 text-[13px] font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">{{ $page }}</button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage" wire:loading.attr="disabled" rel="next" aria-label="Next page"
                    class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
                    <x-heroicon-o-chevron-right class="h-4 w-4" />
                </button>
            @else
                <span class="grid h-9 w-9 place-items-center rounded-lg text-ink-muted opacity-40" aria-disabled="true">
                    <x-heroicon-o-chevron-right class="h-4 w-4" />
                </span>
            @endif
        </div>
    </nav>
@endif
