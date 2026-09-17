@props(['size' => 'md'])

{{-- The visible half of a dropdown: the button and the search-and-pick panel.
     Placed right after the hidden native <select> inside an x-data="combobox"
     wrapper — see resources/js/combobox.js. --}}
<button type="button" x-ref="trigger" x-on:click="toggle()" x-bind:disabled="disabled"
    x-on:keydown.down.prevent="move(1)" x-on:keydown.up.prevent="move(-1)" x-on:keydown.enter.prevent="toggle()" x-on:keydown.space.prevent="toggle()"
    x-bind:aria-expanded="open" aria-haspopup="listbox"
    {{ $attributes->class([
        'flex w-full items-center justify-between gap-2 border text-left transition',
        'focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25',
        'disabled:cursor-not-allowed disabled:bg-sunken disabled:text-ink-muted',
        'min-h-[40px] rounded-lg bg-surface py-2 pl-3 pr-2.5 text-sm max-lg:min-h-[44px]' => $size === 'md',
        // Inline, in running text: a quiet chip that only shows its edge on hover.
        'min-h-[28px] rounded-md bg-transparent py-0.5 pl-1.5 pr-1 text-xs font-medium hover:border-hairline-strong hover:bg-surface' => $size === 'sm',
    ]) }}>
    <span class="truncate" x-text="label || placeholder" x-bind:class="label ? '{{ $size === 'sm' ? 'text-ink-soft' : 'text-ink' }}' : '{{ $size === 'sm' ? 'text-ink-muted' : 'text-ink-soft' }}'"></span>
    <x-heroicon-o-chevron-down class="{{ $size === 'sm' ? 'h-3 w-3' : 'h-4 w-4' }} shrink-0 text-ink-muted transition" x-bind:class="open && 'rotate-180'" />
</button>

{{-- Phone: backdrop behind the sheet. --}}
<div x-show="open" x-cloak x-on:click="close()" x-transition.opacity class="fixed inset-0 z-[60] bg-slate-950/50 backdrop-blur-sm sm:hidden"></div>

<div x-show="open" x-cloak x-on:click.outside="$refs.trigger.contains($event.target) || close()" x-on:keydown.escape.stop="close()"
    x-bind:style="panelStyle()" x-on:resize.window="place()" x-on:scroll.window.passive="place()"
    x-transition:enter="transition duration-100 ease-out" x-transition:enter-start="translate-y-3 opacity-0 sm:translate-y-0 sm:scale-95"
    x-transition:leave="transition duration-75 ease-in" x-transition:leave-end="translate-y-3 opacity-0 sm:translate-y-0 sm:scale-95"
    role="dialog" class="fixed z-[70] flex flex-col overflow-hidden rounded-t-2xl border border-hairline bg-surface elevate-lg max-sm:inset-x-0 max-sm:bottom-0 max-sm:max-h-[80vh] sm:max-h-[min(24rem,70vh)] sm:rounded-xl">

    {{-- Search, only where there is more than one page to search through. --}}
    <div x-show="searchable" class="border-b border-hairline p-2">
        <div class="relative">
            <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
            <input type="search" x-ref="search" x-model="query" x-on:input="limit = {{ 5 }}; active = 0"
                x-on:keydown.down.prevent="move(1)" x-on:keydown.up.prevent="move(-1)" x-on:keydown.enter.prevent="choose()"
                placeholder="Type to search…" autocomplete="off" aria-label="Search options"
                class="min-h-[40px] w-full rounded-lg border border-hairline bg-raised py-2 pl-8 pr-3 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-sm:min-h-[44px]">
        </div>
    </div>

    <ul x-ref="list" role="listbox" class="min-h-0 flex-1 overflow-y-auto p-1">
        <template x-for="(option, index) in visible" :key="option.index">
            <li>
                <p x-show="startsGroup(option, index)" x-text="option.group" class="px-2.5 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wide text-ink-muted"></p>
                <button type="button" role="option" x-on:click="pick(option)" x-on:mousemove="active = index" x-bind:disabled="option.disabled"
                    x-bind:aria-selected="option.value === value"
                    x-bind:class="{
                        'bg-accent-soft text-accent-ink': option.value === value,
                        'bg-list-hover': active === index && option.value !== value,
                        'text-ink-muted italic': option.placeholder,
                        'text-ink': !option.placeholder && option.value !== value,
                        'opacity-50 cursor-not-allowed': option.disabled,
                    }"
                    class="flex min-h-[40px] w-full items-center justify-between gap-2 rounded-md px-2.5 py-2 text-left text-sm transition max-sm:min-h-[44px]">
                    <span class="truncate" x-text="option.text"></span>
                    <x-heroicon-o-check class="h-4 w-4 shrink-0" x-show="option.value === value" />
                </button>
            </li>
        </template>

        <li x-show="matches.length === 0" class="px-2.5 py-3 text-center text-sm text-ink-muted">Nothing matches “<span x-text="query"></span>”.</li>
    </ul>

    <div x-show="remaining > 0" class="border-t border-hairline p-1">
        <button type="button" x-on:click="more()"
            class="flex min-h-[40px] w-full items-center justify-center gap-1.5 rounded-md px-2.5 text-sm font-medium text-accent-ink transition hover:bg-accent-soft max-sm:min-h-[44px]">
            <x-heroicon-o-chevron-double-down class="h-4 w-4" />
            <span>Show more (<span x-text="remaining"></span> more)</span>
        </button>
    </div>
</div>
