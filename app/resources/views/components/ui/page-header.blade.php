@props(['title', 'description' => null, 'back' => null, 'backLabel' => 'Back', 'actions' => null, 'quick' => null])

{{-- The page title with its actions. On a desk the actions sit in a row on
     the right. On a phone that row does not fit, so they fold into a menu
     button (☰) that opens a bottom sheet listing the same buttons — each
     with its own colour and icon — while `quick` (the WhatsApp menu) stays
     out as an icon beside it. --}}
<div x-data="{ actionsOpen: false }" class="mb-5 flex items-start justify-between gap-3">
    <div class="min-w-0 flex-1">
        @if ($back)
            {{-- Returns to the page the person came from when it was one of
                 ours (see back-button.js); the href is the fallback. --}}
            <a href="{{ $back }}" data-back wire:navigate class="mb-1.5 inline-flex items-center gap-1 text-xs font-medium text-ink-muted transition hover:text-ink">
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

    @if ($actions || $quick)
        <div class="flex min-w-0 items-center gap-2 max-sm:shrink-0">
            @if ($quick)
                <div class="flex items-center gap-2">{{ $quick }}</div>
            @endif

            @if ($actions)
                <button type="button" x-on:click="actionsOpen = true" x-bind:aria-expanded="actionsOpen" aria-label="Actions"
                    class="grid h-11 w-11 place-items-center rounded-lg border border-button-secondary-border bg-button-secondary text-button-secondary-ink transition hover:brightness-95 sm:hidden">
                    <x-heroicon-o-bars-3 class="h-5 w-5" />
                </button>

                <div x-show="actionsOpen" x-cloak x-on:click="actionsOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm sm:hidden"></div>

                {{-- The same buttons serve both layouts: a row on a desk; on a
                     phone this box becomes the sheet and every button in it
                     stretches to a full-width row. Any button or link chosen
                     closes the sheet. --}}
                <div x-bind:class="actionsOpen ? '' : 'max-sm:hidden'" x-on:keydown.escape.window="actionsOpen = false"
                    x-on:click="if ($event.target.closest('a, button')) actionsOpen = false"
                    class="flex min-w-0 flex-wrap items-center gap-2 max-sm:fixed max-sm:inset-x-0 max-sm:bottom-0 max-sm:z-50 max-sm:flex-col max-sm:items-stretch max-sm:rounded-t-2xl max-sm:border max-sm:border-hairline max-sm:bg-surface max-sm:p-3 max-sm:pb-[max(0.75rem,env(safe-area-inset-bottom))] max-sm:elevate-lg max-sm:[&>a]:min-h-[48px] max-sm:[&>a]:justify-start max-sm:[&>a]:text-[15px] max-sm:[&>button]:min-h-[48px] max-sm:[&>button]:justify-start max-sm:[&>button]:text-[15px]">
                    <div class="mb-1 flex items-center justify-between gap-3 sm:hidden">
                        <p class="truncate font-[family-name:var(--font-display)] text-sm font-semibold">{{ $title }}</p>
                        <button type="button" x-on:click.stop="actionsOpen = false" class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-ink-muted hover:bg-sunken hover:text-ink" aria-label="Close">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </button>
                    </div>

                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
</div>
