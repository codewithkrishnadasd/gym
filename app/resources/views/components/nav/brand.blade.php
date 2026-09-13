@props(['name', 'initial', 'isPlatform' => false])

<div {{ $attributes->class('flex h-14 shrink-0 items-center gap-2.5 border-b border-hairline px-4') }}>
    <span @class([
        'grid h-8 w-8 shrink-0 place-items-center rounded-lg font-[family-name:var(--font-display)] text-sm font-bold',
        'bg-ink text-app' => $isPlatform,
        'bg-accent text-on-accent' => ! $isPlatform,
    ])>{{ $initial }}</span>

    <span class="min-w-0">
        <span class="block truncate font-[family-name:var(--font-display)] text-sm font-semibold leading-tight">{{ $name }}</span>
        @if ($isPlatform)
            <span class="block text-[11px] font-medium uppercase tracking-[0.12em] text-ink-muted">Root console</span>
        @endif
    </span>
</div>
