{{--
    A date field that always reads and writes dd/mm/yyyy, whatever the browser
    locale, bound to a Livewire property in ISO. Use exactly like x-ui.input
    with wire:model / wire:model.live; the binding is taken from the attribute
    and driven through $wire (resources/js/date-field.js).

    `bare` renders just the control, for filter bars that carry their own
    labels and sizing.
--}}
@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'required' => false,
    'id' => null,
    'bare' => false,
    'size' => 'md',
])

@php
    $bindings = $attributes->whereStartsWith('wire:model')->getAttributes();
    $bindingKey = array_key_first($bindings);
    $property = $bindingKey !== null ? $bindings[$bindingKey] : $name;
    $live = $bindingKey !== null && str_contains($bindingKey, '.live');

    $id ??= $name ? 'f-'.str_replace('.', '-', $name) : 'd-'.substr(md5((string) $property), 0, 8);
    $invalid = $name && $errors->has($name);

    $sizes = [
        'sm' => 'min-h-[36px] px-2.5 text-[13px]',
        'md' => 'min-h-[40px] px-3 py-2 text-sm',
    ];

    $control = ($sizes[$size] ?? $sizes['md']).' numeric w-full rounded-lg border bg-surface pr-9 text-ink placeholder:text-ink-muted transition max-lg:min-h-[44px] focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 disabled:cursor-not-allowed disabled:bg-sunken disabled:text-ink-muted';
    $rest = $attributes->whereDoesntStartWith('wire:model')->except(['class']);
    $wrapperClass = $attributes->only('class')->get('class', '');
@endphp

@if ($bare)
    <x-ui.date-control :property="$property" :live="$live" :id="$id" :name="$name" :required="$required" :invalid="$invalid"
        :control="$control" :rest="$rest" :wrapper-class="$wrapperClass" />
@else
    <x-ui.field :label="$label" :for="$id" :hint="$hint" :name="$name" :required="$required" :class="$wrapperClass">
        <x-ui.date-control :property="$property" :live="$live" :id="$id" :name="$name" :required="$required" :invalid="$invalid"
            :control="$control" :rest="$rest" wrapper-class="" />
    </x-ui.field>
@endif
