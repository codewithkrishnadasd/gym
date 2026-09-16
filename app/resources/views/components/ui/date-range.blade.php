@props(['from' => '', 'to' => '', 'today' => null])

{{--
    A date range as one control: the chosen dates as text, and the shared
    calendar (calendar-panel) where two clicks choose both ends. Applies
    through the component's `setRange(from, to)` — see FiltersByPeriod.
--}}
<div x-data="calendar({
        mode: 'range',
        from: @js($from),
        to: @js($to),
        today: @js($today),
        apply: (from, to) => $wire.setRange(from, to),
    })"
    x-on:keydown.escape.window="close()"
    x-on:open-range-picker.window="open = true; hover = null; view = 'days'; place()"
    {{ $attributes->class('relative') }}>

    {{-- The chosen range, as text rather than a control: the dates are the
         point, and tapping them is how to change them. --}}
    <button type="button" x-ref="trigger" x-on:click="toggle()" :aria-expanded="open"
        class="group inline-flex min-h-[36px] items-baseline gap-2 rounded-lg px-1 text-left transition hover:text-accent max-lg:min-h-[44px]">
        <span class="numeric font-[family-name:var(--font-display)] text-base font-semibold tracking-tight text-ink group-hover:text-accent" x-text="label()">{{ $from && $to ? \Illuminate\Support\Carbon::parse($from)->format('j M Y').' – '.\Illuminate\Support\Carbon::parse($to)->format('j M Y') : 'Pick a date range' }}</span>
        <span x-show="days() > 0" x-cloak class="numeric text-xs text-ink-muted" x-text="days() + (days() === 1 ? ' day' : ' days')"></span>
    </button>

    <x-ui.calendar-panel />
</div>
