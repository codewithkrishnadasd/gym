{{-- The control half of x-ui.date-input; not for direct use. --}}
@props(['property', 'live', 'id', 'name', 'required', 'invalid', 'control', 'rest', 'wrapperClass' => ''])

<div x-data="dateField({ property: @js($property), live: @js($live) })" class="relative {{ $wrapperClass }}">
    <input
        type="text"
        inputmode="numeric"
        autocomplete="off"
        placeholder="dd/mm/yyyy"
        maxlength="10"
        id="{{ $id }}"
        @if ($name) data-field="{{ $name }}" @endif
        @if ($required) required @endif
        @if ($invalid) aria-invalid="true" @endif
        x-model="text"
        x-on:input="onInput"
        x-on:blur="onBlur"
        x-on:keydown.enter.prevent="commit"
        x-bind:class="invalid ? 'border-critical ring-2 ring-critical/20' : ''"
        {{ $rest->class([$control, 'border-critical' => $invalid, 'border-hairline-strong' => ! $invalid]) }}>

    {{-- The same calendar as everywhere else, in single-date mode, for when
         typing is the slow way. It is told the current value when opened
         and hands the pick back through `date-picked`. --}}
    <button type="button" x-on:click="$refs.calendar.dispatchEvent(new CustomEvent('calendar-open', { detail: currentIso() }))" tabindex="-1" aria-label="Open calendar"
        class="absolute inset-y-0 right-0 grid w-9 place-items-center text-ink-muted transition hover:text-accent">
        <x-heroicon-o-calendar class="h-4 w-4" />
    </button>

    <div x-ref="calendar" x-data="calendar({
            mode: 'single',
            today: @js(now(app()->bound('tenant') ? app('tenant')->timezone : config('app.timezone'))->toDateString()),
            futureAllowed: true,
            anchor: () => $el.previousElementSibling,
            apply: (date) => $dispatch('date-picked', date),
        })"
        x-on:calendar-open="openWith($event.detail)"
        x-on:date-picked="onPick($event.detail)"
        x-on:keydown.escape.window="close()">
        <x-ui.calendar-panel />
    </div>
</div>
