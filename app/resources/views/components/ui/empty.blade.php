@props(['icon' => 'inbox', 'title', 'description' => null, 'actions' => null])

{{-- Empty states always offer the relevant next action (MEP 9.2). --}}
<div {{ $attributes->class('flex flex-col items-center justify-center px-6 py-14 text-center') }}>
    <span class="mb-3 grid h-11 w-11 place-items-center rounded-xl bg-sunken text-ink-muted">
        <x-dynamic-component :component="'heroicon-o-'.$icon" class="h-5 w-5" />
    </span>

    <p class="font-[family-name:var(--font-display)] text-sm font-semibold text-ink">{{ $title }}</p>

    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-ink-muted">{{ $description }}</p>
    @endif

    @if ($actions)
        <div class="mt-4 flex flex-wrap items-center justify-center gap-2">{{ $actions }}</div>
    @endif
</div>
