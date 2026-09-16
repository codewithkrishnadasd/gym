@props(['from' => '', 'to' => '', 'today' => null])

{{--
    A date range as one control: a labelled trigger and a calendar popover
    (a bottom sheet on phones) where two clicks choose both ends. Applies via
    the component's `setRange(from, to)` — see FiltersByPeriod.
--}}
<div x-data="dateRange({
        from: @js($from),
        to: @js($to),
        today: @js($today),
        apply: (from, to) => $wire.setRange(from, to),
    })"
    x-on:keydown.escape.window="close()"
    x-on:open-range-picker.window="open = true; hover = null"
    {{ $attributes->class('relative') }}>

    {{-- The chosen range, as text rather than a control: the dates are the
         point, and tapping them is how to change them. --}}
    <button type="button" x-on:click="toggle()" :aria-expanded="open"
        class="group inline-flex min-h-[36px] items-baseline gap-2 rounded-lg px-1 text-left transition hover:text-accent max-lg:min-h-[44px]">
        <span class="numeric font-[family-name:var(--font-display)] text-base font-semibold tracking-tight text-ink group-hover:text-accent" x-text="label()">{{ $from && $to ? \Illuminate\Support\Carbon::parse($from)->format('j M Y').' – '.\Illuminate\Support\Carbon::parse($to)->format('j M Y') : 'Pick a date range' }}</span>
        <span x-show="days() > 0" x-cloak class="numeric text-xs text-ink-muted" x-text="days() + (days() === 1 ? ' day' : ' days')"></span>
    </button>

    {{-- Phone: dim the page behind the sheet. --}}
    <div x-show="open" x-cloak x-transition.opacity x-on:click="close()" class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm sm:hidden"></div>

    <div x-show="open" x-cloak x-on:click.outside="close()"
        x-transition:enter="transition duration-150 ease-out" x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-1"
        x-transition:leave="transition duration-100 ease-in" x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-1"
        class="fixed inset-x-0 bottom-0 z-50 rounded-t-2xl border border-hairline bg-surface p-4 pb-[max(1rem,env(safe-area-inset-bottom))] elevate-lg sm:absolute sm:inset-x-auto sm:bottom-auto sm:left-0 sm:top-full sm:mt-2 sm:w-[38rem] sm:rounded-xl">

        <div class="mb-3 flex items-center justify-between gap-2">
            <button type="button" x-on:click="shift(-1)" aria-label="Previous month"
                class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
                <x-heroicon-o-chevron-left class="h-4 w-4" />
            </button>
            <p class="text-sm font-medium text-ink" x-text="label()"></p>
            <button type="button" x-on:click="shift(1)" aria-label="Next month"
                class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
                <x-heroicon-o-chevron-right class="h-4 w-4" />
            </button>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ([0, 1] as $offset)
                <div @class(['hidden sm:block' => $offset === 1])>
                    <p class="mb-2 text-center text-xs font-semibold uppercase tracking-wide text-ink-muted" x-text="title({{ $offset }})"></p>
                    <div class="grid grid-cols-7 gap-y-1 text-center text-[11px] font-medium text-ink-muted">
                        <template x-for="day in weekdays" :key="day"><span class="py-1" x-text="day"></span></template>
                    </div>
                    <div class="grid grid-cols-7 gap-y-1">
                        <template x-for="(date, index) in cells({{ $offset }})" :key="'{{ $offset }}-' + index">
                            <div class="relative py-0.5"
                                :class="{
                                    'bg-accent-soft': date && inRange(date),
                                    'bg-accent-soft rounded-l-full': date && isStart(date) && (end || hover) && !isEnd(date),
                                    'bg-accent-soft rounded-r-full': date && isEnd(date) && !isStart(date),
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
                <button type="button" x-on:click="quick('last7')" class="rounded-full border border-hairline px-2.5 py-1 text-xs font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">Last 7 days</button>
                <button type="button" x-on:click="quick('last30')" class="rounded-full border border-hairline px-2.5 py-1 text-xs font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">Last 30 days</button>
                <button type="button" x-on:click="quick('last90')" class="rounded-full border border-hairline px-2.5 py-1 text-xs font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">Last 90 days</button>
            </div>
            <p class="text-xs text-ink-muted" x-text="start && !end ? 'Now pick the end date' : 'Pick a start date, then an end date'"></p>
        </div>
    </div>
</div>
