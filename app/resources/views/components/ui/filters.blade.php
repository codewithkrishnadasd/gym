@props(['search' => null, 'placeholder' => 'Search…'])

{{-- The filter bar above every list — see resources/js/filters-bar.js.

     Search stays inline and live. Everything else sits behind one "Filters"
     button that opens a popover (a bottom sheet on phones) where changes are
     staged and sent together on Apply. Applied filters show as chips beside
     the button, each removable on its own. --}}
<div x-data="filtersBar" data-filters
    {{ $attributes->class('border-b border-hairline px-3 py-3 sm:px-4') }}>

    <div class="flex flex-wrap items-center gap-2">
        @if ($search)
            <div class="relative min-w-0 flex-1 basis-48 sm:max-w-xs">
                <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />

                <input type="search" wire:model.live.debounce.400ms="{{ $search }}" placeholder="{{ $placeholder }}"
                    aria-label="{{ $placeholder }}"
                    class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-8 text-sm text-ink placeholder:text-ink-muted transition focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">

                <div wire:loading wire:target="{{ $search }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted">
                    <x-ui.spinner size="xs" />
                </div>
            </div>
        @endif

        <div class="relative shrink-0">
            <button type="button" x-ref="trigger" x-on:click="open ? cancel() : show()" x-bind:aria-expanded="open"
                x-bind:class="active > 0 ? 'border-accent bg-accent-soft text-accent-ink' : 'border-hairline-strong text-ink-soft hover:bg-sunken'"
                class="inline-flex min-h-[40px] items-center gap-1.5 rounded-lg border bg-surface px-3 text-sm font-medium transition max-lg:min-h-[44px]">
                <x-heroicon-o-adjustments-horizontal class="h-4 w-4" />
                <span>Filters</span>
                <span x-show="active > 0" x-cloak x-text="active" class="grid h-5 min-w-5 place-items-center rounded-full bg-accent px-1 text-[11px] font-semibold text-on-accent"></span>
            </button>

            {{-- Phone: backdrop behind the sheet. --}}
            <div x-show="open" x-cloak x-on:click="cancel()" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm sm:hidden"></div>

            {{-- The panel: bottom sheet on phones; on wider screens a popover
                 anchored to the button and positioned `fixed`, so the card's
                 overflow clipping cannot cut it off. --}}
            <div x-show="open" x-cloak x-on:click.outside="cancel()" x-on:keydown.escape.window="open && cancel()" x-trap.noscroll="open"
                x-bind:style="panelStyle()" x-on:resize.window="place()" x-on:scroll.window.passive="place()"
                x-transition:enter="transition duration-150 ease-out" x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                x-transition:leave="transition duration-100 ease-in" x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                role="dialog" aria-modal="true" aria-label="Filters"
                class="fixed z-50 max-h-[85vh] overflow-y-auto rounded-t-2xl border border-hairline bg-surface elevate-lg max-sm:inset-x-0 max-sm:bottom-0 sm:w-[22rem] sm:max-h-[70vh] sm:rounded-xl">
                <div class="flex items-center justify-between gap-3 border-b border-hairline px-4 py-3">
                    <div>
                        <p class="font-[family-name:var(--font-display)] text-sm font-semibold">Filters</p>
                        <p class="text-xs text-ink-muted" x-text="active > 0 ? active + ' applied' : 'Nothing applied'"></p>
                    </div>
                    <button type="button" x-on:click="cancel()" class="grid h-8 w-8 place-items-center rounded-lg text-ink-muted hover:bg-sunken hover:text-ink" aria-label="Close">
                        <x-heroicon-o-x-mark class="h-4 w-4" />
                    </button>
                </div>

                {{-- Selects are staged here (change stopped before Livewire);
                     date fields defer themselves — see filters-bar.js. --}}
                <div x-ref="panel" x-on:change.capture="stage($event)"
                    class="grid gap-3 px-4 py-4 [&>label]:block [&>label]:w-full [&>label>select]:w-full [&_.w-40]:w-full">
                    {{ $slot }}
                </div>

                <div class="flex items-center justify-between gap-2 border-t border-hairline bg-raised px-4 py-3">
                    <button type="button" x-on:click="clearAll()" x-bind:disabled="active === 0"
                        class="text-sm font-medium text-ink-soft hover:text-ink disabled:cursor-not-allowed disabled:opacity-50">Clear all</button>
                    <div class="flex items-center gap-2">
                        <x-ui.button size="sm" variant="ghost" x-on:click="cancel()">Cancel</x-ui.button>
                        <x-ui.button size="sm" variant="primary" icon="check" x-on:click="apply()">Apply filters</x-ui.button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Applied filters, at a glance, each removable. Hidden on phones
             where the badge on the button carries the count. --}}
        <div class="hidden min-w-0 flex-wrap items-center gap-1.5 sm:flex">
            <template x-for="chip in chips" :key="chip.property">
                <span class="inline-flex max-w-full items-center gap-1 rounded-full border border-accent/30 bg-accent-soft py-1 pl-2.5 pr-1 text-xs text-accent-ink">
                    <span class="truncate"><span class="opacity-70" x-text="chip.label + ':'"></span> <span class="font-medium" x-text="chip.text"></span></span>
                    <button type="button" x-on:click="clearOne(chip.property)" class="grid h-5 w-5 shrink-0 place-items-center rounded-full hover:bg-surface" x-bind:aria-label="'Remove ' + chip.label + ' filter'">
                        <x-heroicon-o-x-mark class="h-3 w-3" />
                    </button>
                </span>
            </template>
        </div>
    </div>
</div>
