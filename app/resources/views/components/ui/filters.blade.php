@props(['search' => null, 'placeholder' => 'Search…'])

{{-- Search stays debounced and the whole bar reflows to one column on
     phones so filters never overflow the viewport (MEP 8.4, 9.1). --}}
<div {{ $attributes->class('flex flex-col gap-2 border-b border-hairline px-4 py-3 sm:flex-row sm:flex-wrap sm:items-center') }}>
    @if ($search)
        <div class="relative min-w-0 flex-1 sm:max-w-xs">
            <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />

            <input type="search" wire:model.live.debounce.400ms="{{ $search }}" placeholder="{{ $placeholder }}"
                aria-label="{{ $placeholder }}"
                class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-8 text-sm text-ink placeholder:text-ink-muted transition focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">

            <div wire:loading wire:target="{{ $search }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted">
                <x-ui.spinner size="xs" />
            </div>
        </div>
    @endif

    {{ $slot }}
</div>
