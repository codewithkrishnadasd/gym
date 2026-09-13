@props(['label' => null, 'name' => null, 'hint' => null, 'required' => false, 'id' => null])

@php
    $id ??= $name ? 'f-'.str_replace('.', '-', $name) : null;
    $invalid = $name && $errors->has($name);
@endphp

{{-- `name` is a prop (it keys the error bag), so it must be re-applied to
     the control explicitly — otherwise plain HTML forms would submit no
     value for this field at all. --}}

<x-ui.field :label="$label" :for="$id" :hint="$hint" :name="$name" :required="$required">
    <div class="relative">
        <select
            @if ($name) name="{{ $name }}" @endif
            @if ($id) id="{{ $id }}" @endif
            @if ($invalid) aria-invalid="true" @endif
            {{ $attributes->class([
                'w-full appearance-none rounded-lg border bg-surface py-2 pl-3 pr-9 text-sm text-ink transition',
                'min-h-[40px] max-lg:min-h-[44px]',
                'focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25',
                'disabled:cursor-not-allowed disabled:bg-sunken disabled:text-ink-muted',
                'border-critical' => $invalid,
                'border-hairline-strong' => ! $invalid,
            ]) }}>
            {{ $slot }}
        </select>

        <x-heroicon-o-chevron-down class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
    </div>
</x-ui.field>
