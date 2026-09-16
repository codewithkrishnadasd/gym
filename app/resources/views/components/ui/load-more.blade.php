@props(['list', 'noun' => 'rows'])

{{--
    The foot of a growing list (LoadsMore). The first screen renders with one
    set of rows; this then fetches two more in the background so there is
    always a buffer, and from then on asks for the next set as soon as the
    reader is within about a set of the end — so scrolling never waits on a
    request. The button is there for anyone who would rather tap. Re-keyed
    on every load so each new foot runs its own check.
--}}
@if ($list->hasMore)
    <div wire:key="load-more-{{ $list->count() }}" x-data
        x-init="if ({{ $list->count() }} < {{ 3 * $list->pageSize }}) $wire.loadMore()"
        x-intersect.margin.900px="$wire.loadMore()"
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
