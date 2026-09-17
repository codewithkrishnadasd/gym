@props(['label' => null, 'name' => null, 'hint' => null, 'required' => false, 'id' => null])

@php
    $id ??= $name ? 'f-'.str_replace('.', '-', $name) : null;
    $invalid = $name && $errors->has($name);
@endphp

{{-- `name` is a prop (it keys the error bag), so it must be re-applied to
     the control explicitly — otherwise plain HTML forms would submit no
     value for this field at all.

     The select itself is hidden: what the person sees and uses is the
     combobox after it (type to search, five options at a time), which writes
     its choice back into the select — see resources/js/combobox.js. --}}

<x-ui.field :label="$label" :for="$id" :hint="$hint" :name="$name" :required="$required">
    <div x-data="combobox" x-on:combobox:sync.window="sync()" class="relative">
        {{-- A required choice with exactly one real option is no choice: it is
             picked on load, and the change is sent so a Livewire-bound
             property follows (see select-default.js). --}}
        <select
            @if ($name) name="{{ $name }}" @endif
            @if ($id) id="{{ $id }}" @endif
            @if ($invalid) aria-invalid="true" @endif
            @if ($required) data-select-only-option @endif
            x-on:change="sync()" tabindex="-1" aria-hidden="true"
            {{ $attributes->class('pointer-events-none absolute inset-0 h-full w-full opacity-0') }}>
            {{ $slot }}
        </select>

        <x-ui.combobox :class="$invalid ? 'border-critical' : 'border-hairline-strong'" />
    </div>
</x-ui.field>
