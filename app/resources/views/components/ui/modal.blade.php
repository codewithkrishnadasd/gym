@props(['name', 'title' => null, 'description' => null, 'maxWidth' => 'lg', 'footer' => null])

@php
    $widths = ['sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg', 'xl' => 'max-w-xl', '2xl' => 'max-w-2xl'];
    $width = $widths[$maxWidth] ?? $widths['lg'];
@endphp

{{--
    Opened with `open-modal` and closed with `close-modal`. Alpine's `x-trap`
    keeps keyboard focus inside the dialog while it is open, which the
    accessibility baseline requires (MEP 9.3).

    The event may come from Alpine (`$dispatch('open-modal', 'name')`, detail
    is the string) or from a Livewire component (`$this->dispatch('open-modal',
    'name')`, detail is the positional-params array `['name']`; with no
    arguments it is `[]`). `target()` reads both shapes the same way.
--}}
<div
    x-data="{
        open: false,
        target(detail) {
            if (Array.isArray(detail)) { return detail[0] }
            if (detail && typeof detail === 'object') { return detail.name }
            return detail
        },
    }"
    x-on:open-modal.window="if (target($event.detail) === '{{ $name }}') { open = true }"
    x-on:close-modal.window="if (target($event.detail) === '{{ $name }}' || target($event.detail) === undefined) { open = false }"
    x-on:keydown.escape.window="open = false"
    x-cloak
    x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true">
    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

    <div class="flex min-h-full items-end justify-center p-0 sm:items-center sm:p-4">
        <div
            x-show="open"
            x-trap.noscroll="open"
            x-transition:enter="transition duration-150 ease-out"
            x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
            x-transition:leave="transition duration-100 ease-in"
            x-transition:leave-end="opacity-0 sm:scale-95"
            class="relative w-full {{ $width }} rounded-t-2xl border border-hairline bg-surface elevate-lg sm:rounded-xl">
            <header class="flex items-start justify-between gap-3 border-b border-hairline px-5 py-3.5">
                <div class="min-w-0">
                    @if ($title)
                        <h2 class="font-[family-name:var(--font-display)] text-sm font-semibold">{{ $title }}</h2>
                    @endif
                    @if ($description)
                        <p class="mt-0.5 text-xs text-ink-muted">{{ $description }}</p>
                    @endif
                </div>

                <button type="button" @click="open = false" aria-label="Close"
                    class="-mr-1.5 -mt-1 grid h-9 w-9 shrink-0 place-items-center rounded-lg text-ink-muted transition hover:bg-sunken hover:text-ink">
                    <x-heroicon-o-x-mark class="h-4.5 w-4.5" />
                </button>
            </header>

            <div class="px-5 py-4">{{ $slot }}</div>

            @if ($footer)
                <footer class="flex flex-wrap items-center justify-end gap-2 border-t border-hairline bg-raised px-5 py-3">
                    {{ $footer }}
                </footer>
            @endif
        </div>
    </div>
</div>
