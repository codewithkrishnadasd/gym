@props([
    'type' => 'line',
    'labels' => [],
    'datasets' => [],
    'height' => 220,
    'stacked' => false,
    'legend' => null,
    'valueFormat' => 'number',
    'currencySymbol' => null,
    'summary' => null,
])

@php
    $spec = [
        'type' => $type,
        'labels' => $labels,
        'datasets' => $datasets,
        'stacked' => $stacked,
        'valueFormat' => $valueFormat,
        'currencySymbol' => $currencySymbol,
    ];

    if ($legend !== null) {
        $spec['legend'] = $legend;
    }

    $hasData = collect($datasets)->contains(fn (array $set): bool => collect($set['data'] ?? [])->contains(fn ($v) => (float) $v !== 0.0));
@endphp

<div {{ $attributes }}>
    @if ($hasData)
        <div style="height: {{ $height }}px" class="relative">
            <canvas data-chart="{{ json_encode($spec, JSON_THROW_ON_ERROR) }}"
                role="img"
                aria-label="{{ $summary ?? 'Chart' }}"></canvas>
        </div>

        {{-- The same figures in text, for screen readers and no-JS clients. --}}
        @if ($summary)
            <p class="sr-only">{{ $summary }}</p>
        @endif
    @else
        <div style="height: {{ $height }}px" class="grid place-items-center rounded-lg border border-dashed border-hairline">
            <p class="text-xs text-ink-muted">Not enough data for this period yet.</p>
        </div>
    @endif
</div>
