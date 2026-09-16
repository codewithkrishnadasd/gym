{{-- The control half of x-ui.date-input; not for direct use. --}}
@props(['property', 'live', 'id', 'name', 'required', 'invalid', 'control', 'rest', 'wrapperClass' => ''])

{{--
    The chosen date as text ("16 Sep 2026", or "Select date" while empty);
    tapping it opens the shared calendar in single-date mode — the same
    control the dashboard's range uses, with one date. Optional fields get
    an × to clear. Bound to the Livewire property in ISO through
    date-field.js.
--}}
@php
    // HTML lowercases attribute names, so the event that opens this field's
    // calendar has to be lowercase on both sides.
    $openEvent = 'calendar-open-'.strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $id) ?? '');
@endphp

<div x-data="dateField({ property: @js($property), live: @js($live) })" class="relative {{ $wrapperClass }}">
    <button type="button" x-ref="trigger"
        id="{{ $id }}"
        @if ($name) data-field="{{ $name }}" @endif
        @if ($invalid) aria-invalid="true" @endif
        x-on:click="$dispatch('{{ $openEvent }}', iso)"
        x-bind:aria-expanded="pickerOpen"
        {{ $rest->class([$control, 'flex items-center justify-between gap-2 text-left', 'border-critical' => $invalid, 'border-hairline-strong' => ! $invalid]) }}>
        <span class="numeric truncate" x-text="display || 'Select date'" x-bind:class="display ? 'text-ink' : 'text-ink-muted'">Select date</span>
        <x-heroicon-o-calendar-days class="h-4 w-4 shrink-0 text-ink-muted" x-show="!display || {{ $required ? 'true' : 'false' }}" />
    </button>

    @unless ($required)
        <button type="button" x-show="display" x-cloak x-on:click.stop="clear()" tabindex="-1" aria-label="Clear date"
            class="absolute inset-y-0 right-0 grid w-9 place-items-center text-ink-muted transition hover:text-critical">
            <x-heroicon-o-x-mark class="h-4 w-4" />
        </button>
    @endunless

    {{-- Its own scope, so it is addressed by a page-unique event rather than
         a ref (an element with x-data keeps its refs to itself). --}}
    <div x-data="calendar({
            mode: 'single',
            today: @js(now(app()->bound('tenant') ? app('tenant')->timezone : config('app.timezone'))->toDateString()),
            futureAllowed: true,
            anchor: () => $el.parentElement.querySelector('[x-ref=trigger]'),
            apply: (date) => $dispatch('date-picked', date),
        })"
        x-on:{{ $openEvent }}.window="openWith($event.detail)"
        x-on:date-picked="onPick($event.detail)"
        x-on:keydown.escape.window="close()"
        x-effect="pickerOpen = open">
        <x-ui.calendar-panel />
    </div>
</div>
