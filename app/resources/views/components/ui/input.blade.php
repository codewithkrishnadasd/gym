@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'hint' => null,
    'required' => false,
    'prefix' => null,
    'id' => null,
])

@php
    $id ??= $name ? 'f-'.str_replace('.', '-', $name) : null;
    $invalid = $name && $errors->has($name);
@endphp

{{-- `name` is a prop (it keys the error bag), so it must be re-applied to
     the control explicitly — otherwise plain HTML forms would submit no
     value for this field at all. --}}

@if ($type === 'date')
    {{-- Every date in the app is entered as dd/mm/yyyy, whatever the browser
         would do with a native date input. --}}
    <x-ui.date-input :label="$label" :name="$name" :hint="$hint" :required="$required" :id="$id" {{ $attributes }} />
@else
<x-ui.field :label="$label" :for="$id" :hint="$hint" :name="$name" :required="$required">
    <div class="relative">
        @if ($prefix)
            <span class="pointer-events-none absolute inset-y-0 left-0 grid w-9 place-items-center text-sm text-ink-muted">{{ $prefix }}</span>
        @endif

        <input
            type="{{ $type }}"
            @if ($name) name="{{ $name }}" @endif
            @if ($id) id="{{ $id }}" @endif
            @if ($invalid) aria-invalid="true" @endif
            {{ $attributes->class([
                'w-full rounded-lg border bg-surface px-3 py-2 text-sm text-ink placeholder:text-ink-muted transition',
                'min-h-[40px] max-lg:min-h-[44px]',
                'focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25',
                'disabled:cursor-not-allowed disabled:bg-sunken disabled:text-ink-muted',
                'pl-9' => (bool) $prefix,
                'border-critical' => $invalid,
                'border-hairline-strong' => ! $invalid,
            ]) }}>
    </div>
</x-ui.field>
@endif
