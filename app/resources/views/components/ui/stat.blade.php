@props([
    'label',
    'value',
    'icon' => null,
    'hint' => null,
    'delta' => null,
    'deltaTone' => 'neutral',
    'href' => null,
    'tone' => 'neutral',
])

@php
    $tag = $href ? 'a' : 'div';

    $accentRing = [
        'neutral' => 'text-ink-muted bg-sunken',
        'positive' => 'text-positive bg-positive-soft',
        'caution' => 'text-caution bg-caution-soft',
        'critical' => 'text-critical bg-critical-soft',
        'accent' => 'text-accent bg-accent-soft',
        'info' => 'text-info bg-info-soft',
    ][$tone] ?? 'text-ink-muted bg-sunken';

    $deltaColour = [
        'positive' => 'text-positive',
        'critical' => 'text-critical',
        'caution' => 'text-caution',
        'neutral' => 'text-ink-muted',
    ][$deltaTone] ?? 'text-ink-muted';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    {{ $attributes->class([
        'block rounded-xl border border-hairline bg-surface p-4 elevate transition',
        'hover:border-hairline-strong hover:elevate-lg' => (bool) $href,
    ]) }}>
    <div class="flex items-start justify-between gap-3">
        <p class="text-[13px] font-medium text-ink-soft">{{ $label }}</p>
        @if ($icon)
            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg {{ $accentRing }}">
                <x-dynamic-component :component="'heroicon-o-'.$icon" class="h-4 w-4" />
            </span>
        @endif
    </div>

    <p class="numeric mt-2 font-[family-name:var(--font-display)] text-2xl font-semibold tracking-tight">{{ $value }}</p>

    @if ($delta || $hint)
        <p class="mt-1 flex items-center gap-1.5 text-xs">
            @if ($delta)
                <span class="numeric font-medium {{ $deltaColour }}">{{ $delta }}</span>
            @endif
            @if ($hint)
                <span class="text-ink-muted">{{ $hint }}</span>
            @endif
        </p>
    @endif
</{{ $tag }}>
