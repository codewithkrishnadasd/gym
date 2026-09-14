@props(['label' => null, 'description' => null, 'id' => null])

@php
    $id ??= 'c-'.uniqid();
    // A locked box is still a real, ticked box — it just cannot be turned off
    // here — so the label loses its click affordance rather than its clarity.
    $locked = filter_var($attributes->get('disabled', false), FILTER_VALIDATE_BOOL);
@endphp

<label for="{{ $id }}"
    @class([
        'flex min-h-[44px] items-start gap-2.5 rounded-lg px-2 py-2 transition lg:min-h-0',
        'cursor-pointer hover:bg-sunken' => ! $locked,
        'cursor-default opacity-70' => $locked,
    ])>
    <input type="checkbox" id="{{ $id }}"
        {{ $attributes->class('mt-0.5 h-4 w-4 shrink-0 rounded border-hairline-strong text-accent accent-[var(--c-accent)] focus:ring-2 focus:ring-accent/25') }}>

    <span class="min-w-0">
        <span class="block text-sm leading-snug text-ink">{{ $label ?? $slot }}</span>
        @if ($description)
            <span class="block text-xs text-ink-muted">{{ $description }}</span>
        @endif
    </span>
</label>
