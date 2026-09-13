@props(['label' => null, 'for' => null, 'hint' => null, 'name' => null, 'required' => false])

@php $error = $name ? $errors->first($name) : null; @endphp

<div {{ $attributes->class('space-y-1.5') }}>
    @if ($label)
        <label @if ($for) for="{{ $for }}" @endif class="block text-[13px] font-medium text-ink-soft">
            {{ $label }}
            @if ($required)<span class="text-critical" aria-hidden="true">*</span>@endif
        </label>
    @endif

    {{ $slot }}

    @if ($error)
        <p class="flex items-start gap-1 text-xs text-critical" role="alert">
            <x-heroicon-o-exclamation-circle class="mt-px h-3.5 w-3.5 shrink-0" />
            <span>{{ $error }}</span>
        </p>
    @elseif ($hint)
        <p class="text-xs text-ink-muted">{{ $hint }}</p>
    @endif
</div>
