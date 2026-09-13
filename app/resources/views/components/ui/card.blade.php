@props(['title' => null, 'description' => null, 'padded' => true, 'actions' => null])

<section {{ $attributes->class('overflow-hidden rounded-xl border border-hairline bg-surface elevate') }}>
    @if ($title || $actions)
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-hairline px-4 py-3 sm:px-5">
            <div class="min-w-0">
                @if ($title)
                    <h2 class="font-[family-name:var(--font-display)] text-sm font-semibold">{{ $title }}</h2>
                @endif
                @if ($description)
                    <p class="mt-0.5 text-xs text-ink-muted">{{ $description }}</p>
                @endif
            </div>
            @if ($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endif
        </header>
    @endif

    <div @class(['px-4 py-4 sm:px-5' => $padded])>
        {{ $slot }}
    </div>
</section>
