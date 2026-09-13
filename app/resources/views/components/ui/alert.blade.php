@props(['tone' => 'info', 'title' => null, 'icon' => null])

@php
    $tones = [
        'info' => ['border-info/25 bg-info-soft text-info', 'information-circle'],
        'positive' => ['border-positive/25 bg-positive-soft text-positive', 'check-circle'],
        'caution' => ['border-caution/25 bg-caution-soft text-caution', 'exclamation-triangle'],
        'critical' => ['border-critical/25 bg-critical-soft text-critical', 'exclamation-circle'],
    ];

    [$classes, $defaultIcon] = $tones[$tone] ?? $tones['info'];
@endphp

<div role="status" {{ $attributes->class("flex items-start gap-2.5 rounded-lg border px-3.5 py-3 text-sm $classes") }}>
    <x-dynamic-component :component="'heroicon-o-'.($icon ?? $defaultIcon)" class="mt-px h-[18px] w-[18px] shrink-0" />

    <div class="min-w-0 flex-1">
        @if ($title)
            <p class="font-medium">{{ $title }}</p>
        @endif
        <div @class(['text-current', 'mt-0.5 opacity-90' => (bool) $title])>{{ $slot }}</div>
    </div>
</div>
