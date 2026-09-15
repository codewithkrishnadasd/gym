@props(['search' => null, 'placeholder' => 'Search…'])

{{-- The filter bar above every list. On a phone the selects would stack into
     a column taller than the list itself, so there they sit behind one
     "Filters" button that shows how many are active; from `sm` up everything
     is inline as before. The count is read from the controls themselves, so
     the component needs to know nothing about what it wraps. --}}
<div x-data="{
        open: false,
        active: 0,
        count() {
            this.active = Array.from(this.$refs.panel.querySelectorAll('select, input')).filter((el) => {
                if (el.type === 'hidden' || el.type === 'checkbox' || el.type === 'radio' || el.type === 'button') return false;
                if (el.dataset.filterDefault !== undefined) return el.value !== el.dataset.filterDefault;
                return el.value !== '';
            }).length;
        },
    }"
    x-init="count(); $nextTick(() => count())"
    x-on:change="count()" x-on:input.debounce.300ms="count()"
    {{ $attributes->class('flex flex-col gap-2 border-b border-hairline px-3 py-3 sm:flex-row sm:flex-wrap sm:items-center sm:px-4') }}>

    <div class="flex min-w-0 items-center gap-2 sm:contents">
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

        {{-- Phone only: the toggle for the filter panel, with the active count. --}}
        <button type="button" x-on:click="open = ! open" x-bind:aria-expanded="open"
            x-bind:class="active > 0 ? 'border-accent text-accent bg-accent-soft' : 'border-hairline-strong text-ink-soft'"
            class="inline-flex min-h-[44px] shrink-0 items-center gap-1.5 rounded-lg border bg-surface px-3 text-sm font-medium transition sm:hidden">
            <x-heroicon-o-adjustments-horizontal class="h-4 w-4" />
            <span>Filters</span>
            <span x-show="active > 0" x-text="active" class="grid h-5 min-w-5 place-items-center rounded-full bg-accent px-1 text-[11px] font-semibold text-on-accent"></span>
            <x-heroicon-o-chevron-down class="h-3.5 w-3.5 transition-transform" x-bind:class="open && 'rotate-180'" />
        </button>
    </div>

    {{-- One copy of the controls. On a phone they drop below the search as a
         single column and show only while open; from `sm` up the wrapper
         dissolves (`contents`) so they sit inline with the search as before. --}}
    <div x-ref="panel"
        x-bind:class="open ? 'grid grid-cols-1 gap-2' : 'hidden'"
        class="sm:contents! [&>label]:w-full [&>label>select]:w-full sm:[&>label]:w-auto sm:[&>label>select]:w-auto">
        {{ $slot }}
    </div>
</div>
