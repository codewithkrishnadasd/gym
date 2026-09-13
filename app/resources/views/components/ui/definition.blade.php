@props(['label', 'value' => null])

<div {{ $attributes->class('py-2') }}>
    <dt class="text-xs font-medium uppercase tracking-[0.04em] text-ink-muted">{{ $label }}</dt>
    <dd class="mt-0.5 text-sm text-ink">{{ $value ?? $slot }}</dd>
</div>
