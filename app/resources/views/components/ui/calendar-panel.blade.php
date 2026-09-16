{{--
    The calendar popover body, inside a `calendar` Alpine scope (calendar.js).
    A bottom sheet on phones; on wider screens a `fixed` panel placed by
    place() so it always sits inside the viewport. Days by default; the
    month and year in the heading each open a grid for jumping quickly.
--}}
<div x-show="open" x-cloak x-on:click="close()" x-transition.opacity class="fixed inset-0 z-[60] bg-slate-950/50 backdrop-blur-sm sm:hidden"></div>

<div x-show="open" x-cloak x-on:click.outside="close()"
    x-bind:style="panelStyle()" x-on:resize.window="place()" x-on:scroll.window.passive="place()"
    x-transition:enter="transition duration-150 ease-out" x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-1"
    x-transition:leave="transition duration-100 ease-in" x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-1"
    role="dialog" aria-modal="true" aria-label="Calendar"
    class="fixed inset-x-0 bottom-0 z-[70] max-h-[85vh] overflow-y-auto rounded-t-2xl border border-hairline bg-surface p-4 pb-[max(1rem,env(safe-area-inset-bottom))] elevate-lg sm:inset-x-auto sm:bottom-auto sm:max-h-[80vh] sm:rounded-xl">

    {{-- Heading: arrows step a month (or a year / a dozen years while
         those grids are open); the month and year names open the grids. --}}
    <div class="mb-3 flex items-center justify-between gap-2">
        <button type="button" x-on:click="view === 'days' ? shift(-1) : shiftYears(-1)" aria-label="Previous"
            class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
            <x-heroicon-o-chevron-left class="h-4 w-4" />
        </button>

        <div class="flex items-center gap-1 text-sm font-semibold text-ink">
            <template x-if="view === 'years'">
                <span x-text="yearRangeLabel()"></span>
            </template>
            <template x-if="view !== 'years'">
                <span class="inline-flex items-center gap-1">
                    <button type="button" x-on:click="showMonths()" x-show="!twoMonths() || view === 'months'"
                        class="rounded-md px-2 py-1 transition hover:bg-sunken" x-bind:class="view === 'months' && 'bg-accent-soft text-accent-ink'"
                        x-text="monthName(0)"></button>
                    <span x-show="twoMonths() && view === 'days'" class="px-1 text-ink-soft" x-text="label()"></span>
                    <button type="button" x-on:click="showYears()" class="rounded-md px-2 py-1 transition hover:bg-sunken" x-text="cursor ? cursor.getFullYear() : ''"></button>
                </span>
            </template>
        </div>

        <button type="button" x-on:click="view === 'days' ? shift(1) : shiftYears(1)" aria-label="Next"
            class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
            <x-heroicon-o-chevron-right class="h-4 w-4" />
        </button>
    </div>

    {{-- Months of the shown year. --}}
    <div x-show="view === 'months'" x-cloak class="grid grid-cols-3 gap-1.5">
        <template x-for="(name, index) in months" :key="name">
            <button type="button" x-on:click="pickMonth(index)" x-text="name"
                class="h-11 rounded-lg text-sm font-medium transition"
                x-bind:class="isCurrentMonth(index) ? 'bg-accent text-on-accent' : 'text-ink hover:bg-sunken'"></button>
        </template>
    </div>

    {{-- A dozen years at a time. --}}
    <div x-show="view === 'years'" x-cloak class="grid grid-cols-3 gap-1.5">
        <template x-for="year in years()" :key="year">
            <button type="button" x-on:click="pickYear(year)" x-text="year"
                x-bind:disabled="!futureAllowed && today && year > Number(today.slice(0, 4))"
                class="numeric h-11 rounded-lg text-sm font-medium transition disabled:opacity-30"
                x-bind:class="cursor && year === cursor.getFullYear() ? 'bg-accent text-on-accent' : 'text-ink hover:bg-sunken'"></button>
        </template>
    </div>

    {{-- Days: one month, or two side by side for a range on a wide screen. --}}
    <div x-show="view === 'days'" class="grid gap-5" x-bind:class="twoMonths() && 'sm:grid-cols-2'">
        @foreach ([0, 1] as $offset)
            <div @if ($offset === 1) x-show="twoMonths()" x-cloak @endif>
                <p x-show="twoMonths()" x-cloak class="mb-2 text-center text-xs font-semibold uppercase tracking-wide text-ink-muted" x-text="monthName({{ $offset }}) + ' ' + yearOf({{ $offset }})"></p>
                <div class="grid grid-cols-7 gap-y-1 text-center text-[11px] font-medium text-ink-muted">
                    <template x-for="day in weekdays" :key="day"><span class="py-1" x-text="day"></span></template>
                </div>
                <div class="grid grid-cols-7 gap-y-1">
                    <template x-for="(date, index) in cells({{ $offset }})" :key="'{{ $offset }}-' + index">
                        <div class="relative py-0.5"
                            :class="{
                                'bg-accent-soft': date && inRange(date),
                                'bg-accent-soft rounded-l-full': date && mode === 'range' && isStart(date) && (end || hover) && !isEnd(date),
                                'bg-accent-soft rounded-r-full': date && mode === 'range' && isEnd(date) && !isStart(date),
                            }">
                            <button type="button" x-show="date" x-on:click="pick(date)" x-on:mouseenter="hover = date" x-on:mouseleave="hover = null"
                                :disabled="date && isFuture(date)"
                                :aria-pressed="date && (isStart(date) || isEnd(date))"
                                class="numeric relative mx-auto grid h-9 w-9 place-items-center rounded-full text-sm transition disabled:cursor-not-allowed disabled:opacity-30"
                                :class="{
                                    'bg-accent text-on-accent font-semibold shadow-sm': date && (isStart(date) || isEnd(date)),
                                    'text-ink hover:bg-sunken': date && !isStart(date) && !isEnd(date) && !inRange(date),
                                    'text-accent-ink': date && inRange(date),
                                    'ring-1 ring-inset ring-accent/60': date === today && !isStart(date) && !isEnd(date),
                                }"
                                x-text="date ? Number(date.slice(8, 10)) : ''"></button>
                        </div>
                    </template>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-hairline pt-3">
        <div class="flex flex-wrap gap-1.5">
            <button type="button" x-on:click="goToday()" class="rounded-full border border-hairline px-2.5 py-1 text-xs font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">Today</button>
            <template x-if="mode === 'range'">
                <span class="inline-flex gap-1.5">
                    <button type="button" x-on:click="quick('last7')" class="rounded-full border border-hairline px-2.5 py-1 text-xs font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">Last 7 days</button>
                    <button type="button" x-on:click="quick('last30')" class="rounded-full border border-hairline px-2.5 py-1 text-xs font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">Last 30 days</button>
                    <button type="button" x-on:click="quick('last90')" class="rounded-full border border-hairline px-2.5 py-1 text-xs font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">Last 90 days</button>
                </span>
            </template>
        </div>
        <p class="text-xs text-ink-muted" x-text="mode === 'single' ? 'Tap the month or year to jump.' : (start && !end ? 'Now pick the end date' : 'Pick a start date, then an end date')"></p>
    </div>
</div>
