@props(['label' => null, 'name' => null, 'hint' => null, 'required' => false, 'rows' => 3, 'id' => null])

@php
    $id ??= $name ? 'f-'.str_replace('.', '-', $name) : null;
    $invalid = $name && $errors->has($name);
@endphp

{{-- `name` is a prop (it keys the error bag), so it must be re-applied to
     the control explicitly — otherwise plain HTML forms would submit no
     value for this field at all. --}}

<x-ui.field :label="$label" :for="$id" :hint="$hint" :name="$name" :required="$required">
    <textarea
        rows="{{ $rows }}"
        @if ($name) name="{{ $name }}" @endif
        @if ($id) id="{{ $id }}" @endif
        @if ($invalid) aria-invalid="true" @endif
        {{ $attributes->class([
            'w-full rounded-lg border bg-surface px-3 py-2 text-sm text-ink placeholder:text-ink-muted transition',
            'focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25',
            'border-critical' => $invalid,
            'border-hairline-strong' => ! $invalid,
        ]) }}>{{ $slot }}</textarea>
</x-ui.field>
