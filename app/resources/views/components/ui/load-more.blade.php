@props(['list', 'noun' => 'rows'])

{{--
    The foot of a growing list (LoadsMore). Coming into view asks for the next
    rows; the button is there for anyone who would rather tap, and for the
    moment before the observer fires. Re-keyed on every load so a list that
    fits the screen keeps filling until it does not.
--}}
@if ($list->hasMore)
    <div wire:key="load-more-{{ $list->count() }}" x-data x-intersect.margin.320px="$wire.loadMore()"
        class="flex flex-col items-center gap-2 border-t border-hairline px-4 py-4">
        <button type="button" wire:click="loadMore" wire:loading.attr="disabled" wire:target="loadMore"
            class="inline-flex h-9 items-center gap-2 rounded-full border border-hairline bg-surface px-4 text-xs font-medium text-ink-soft transition hover:border-hairline-strong hover:text-ink disabled:opacity-60">
            <span wire:loading.remove wire:target="loadMore">Load more</span>
            <span wire:loading wire:target="loadMore" class="inline-flex items-center gap-1.5"><x-ui.spinner size="xs" /> Loading…</span>
        </button>
        <p class="numeric text-[11px] text-ink-muted">Showing {{ number_format($list->count()) }} of {{ number_format($list->total) }} {{ $noun }}</p>
    </div>
@elseif ($list->count() > $list->pageSize)
    <p class="numeric border-t border-hairline px-4 py-3 text-center text-[11px] text-ink-muted">All {{ number_format($list->total) }} {{ $noun }} shown</p>
@endif
