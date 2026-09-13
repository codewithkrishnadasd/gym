@props(['title', 'description' => null, 'back' => null, 'backLabel' => 'Back', 'actions' => null])

<div class="mb-5 flex flex-wrap items-start justify-between gap-3">
    <div class="min-w-0">
        @if ($back)
            <a href="{{ $back }}" class="mb-1.5 inline-flex items-center gap-1 text-xs font-medium text-ink-muted transition hover:text-ink">
                <x-heroicon-o-arrow-left class="h-3.5 w-3.5" />
                {{ $backLabel }}
            </a>
        @endif

        <h1 class="truncate font-[family-name:var(--font-display)] text-xl font-semibold tracking-tight sm:text-2xl">
            {{ $title }}
        </h1>

        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-ink-soft">{{ $description }}</p>
        @endif
    </div>

    @if ($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endif
</div>
