{{-- Loading state for a list card. The rows stay where they are, dimmed,
     with a thin sweeping bar along the top and a small spinner — so a filter
     change reads as "updating", not as the page being replaced. Needs the
     card to be `relative` (x-ui.card is). --}}
@props(['target' => null])

{{-- Hidden while the only thing in flight is "load more" (see
     load-more.js): rows already on screen stay usable as the next set
     arrives underneath them. --}}
<div wire:loading.delay.short @if ($target) wire:target="{{ $target }}" @endif
    class="list-loader pointer-events-none absolute inset-0 z-10 rounded-xl bg-surface/55" aria-live="polite" aria-label="Updating">
    <div class="absolute inset-x-0 top-0 h-0.5 overflow-hidden">
        <div class="list-loader__sweep h-full w-1/3 rounded-full bg-accent"></div>
    </div>
    <div class="absolute left-1/2 top-16 -translate-x-1/2 rounded-full border border-hairline bg-surface px-3 py-1.5 text-xs font-medium text-ink-soft shadow-sm">
        <span class="inline-flex items-center gap-1.5"><x-ui.spinner size="xs" /> Updating…</span>
    </div>
</div>
