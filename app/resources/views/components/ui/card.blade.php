@props(['title' => null, 'description' => null, 'padded' => true, 'actions' => null, 'collapsible' => false, 'open' => true])

{{-- `collapsible` turns the header into a toggle for the body; `open` sets the
     starting state. The header actions stay clickable without toggling. --}}
<section {{ $attributes->class('overflow-hidden rounded-xl border border-hairline bg-surface elevate') }}
    @if ($collapsible) x-data="{ open: @js((bool) $open) }" @endif>
    @if ($title || $actions)
        <header @class(['flex flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-5', 'border-b border-hairline' => ! $collapsible])
            @if ($collapsible) x-bind:class="open ? 'border-b border-hairline' : ''" @endif>
            @if ($collapsible)
                <button type="button" x-on:click="open = ! open" x-bind:aria-expanded="open"
                    class="flex min-w-0 flex-1 items-center gap-2 text-left">
                    <x-heroicon-o-chevron-right class="h-4 w-4 shrink-0 text-ink-muted transition-transform duration-200" x-bind:class="open && 'rotate-90'" />
                    <span class="min-w-0">
                        @if ($title)
                            <span class="block font-[family-name:var(--font-display)] text-sm font-semibold">{{ $title }}</span>
                        @endif
                        @if ($description)
                            <span class="mt-0.5 block text-xs text-ink-muted">{{ $description }}</span>
                        @endif
                    </span>
                </button>
            @else
                <div class="min-w-0">
                    @if ($title)
                        <h2 class="font-[family-name:var(--font-display)] text-sm font-semibold">{{ $title }}</h2>
                    @endif
                    @if ($description)
                        <p class="mt-0.5 text-xs text-ink-muted">{{ $description }}</p>
                    @endif
                </div>
            @endif
            @if ($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endif
        </header>
    @endif

    <div @class(['px-4 py-4 sm:px-5' => $padded]) @if ($collapsible) x-show="open" x-collapse @endif>
        {{ $slot }}
    </div>
</section>
