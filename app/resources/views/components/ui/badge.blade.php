@props(['tone' => 'neutral', 'dot' => true])

@php
    $tones = [
        'neutral' => ['bg-sunken text-ink-soft', 'bg-ink-muted'],
        'positive' => ['bg-positive-soft text-positive', 'bg-positive'],
        'caution' => ['bg-caution-soft text-caution', 'bg-caution'],
        'critical' => ['bg-critical-soft text-critical', 'bg-critical'],
        'info' => ['bg-info-soft text-info', 'bg-info'],
        'accent' => ['bg-accent-soft text-accent-ink', 'bg-accent'],
    ];

    [$chip, $dotColour] = $tones[$tone] ?? $tones['neutral'];
@endphp

{{-- Status is never colour alone: the label always carries the meaning (MEP 9.1). --}}
<span {{ $attributes->class("inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-xs font-medium whitespace-nowrap $chip") }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotColour }}"></span>
    @endif
    {{ $slot }}
</span>
