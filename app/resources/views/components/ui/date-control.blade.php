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

    {{-- Calendar picker, for when typing is the slow way. --}}
    <button type="button" x-on:click="openPicker" tabindex="-1" aria-label="Open calendar"
        class="absolute inset-y-0 right-0 grid w-9 place-items-center text-ink-muted transition hover:text-accent">
        <x-heroicon-o-calendar class="h-4 w-4" />
    </button>
    <input type="date" x-ref="picker" x-on:change="onPick" tabindex="-1" aria-hidden="true"
        class="pointer-events-none absolute bottom-0 right-0 h-0 w-0 opacity-0">
</div>
