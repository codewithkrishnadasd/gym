@props(['sections', 'brandName', 'account' => null, 'roleLabel' => null, 'isPlatform' => false])

{{--
    Full-screen menu: every destination as a tile, grouped under its section.
    Opened by the Menu button (or the hamburger) when the viewer has chosen
    the launcher style. Tiles are wire:navigate links; picking one closes it.
--}}
<div x-data="{ open: false }"
    x-on:open-launcher.window="open = true"
    x-on:close-launcher.window="open = false"
    x-on:livewire:navigated.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open" x-cloak
    class="fixed inset-0 z-50 overflow-y-auto bg-app/95 backdrop-blur-xl" role="dialog" aria-modal="true" aria-label="Menu">

    <div x-show="open" x-trap.noscroll="open"
        x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="translate-y-3 opacity-0"
        x-transition:leave="transition duration-150 ease-in" x-transition:leave-end="translate-y-3 opacity-0"
        class="mx-auto flex min-h-full w-full max-w-6xl flex-col px-4 pb-[max(1.5rem,env(safe-area-inset-bottom))] pt-4 sm:px-8 sm:pt-6">

        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-muted">Menu</p>
                <p class="truncate font-[family-name:var(--font-display)] text-lg font-semibold text-ink">{{ $brandName }}</p>
            </div>
            <button type="button" x-on:click="open = false" aria-label="Close menu"
                class="grid h-11 w-11 place-items-center rounded-full border border-hairline bg-surface text-ink-soft transition hover:bg-sunken hover:text-ink">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>

        <div class="mt-6 flex-1 space-y-8">
            @foreach ($sections as $section)
                <section>
                    {{-- Section rail: a short accent line and the heading, so groups
                         read as groups even when the grid wraps to two columns. --}}
                    <div class="mb-3 flex items-center gap-3">
                        <span class="h-px w-6 shrink-0 bg-accent"></span>
                        <h2 class="text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-soft">{{ $section['heading'] ?? 'Overview' }}</h2>
                        <span class="h-px flex-1 bg-hairline"></span>
                    </div>

                    <ul class="grid grid-cols-2 gap-2.5 sm:grid-cols-3 sm:gap-3 lg:grid-cols-4 xl:grid-cols-5">
                        @foreach ($section['items'] as $item)
                            @php $active = request()->routeIs($item['active']); @endphp
                            <li>
                                <a href="{{ route($item['route']) }}" wire:navigate x-on:click="open = false"
                                    @if ($active) aria-current="page" @endif
                                    @class([
                                        'group flex min-h-[104px] flex-col justify-between rounded-2xl border p-3.5 transition sm:min-h-[120px] sm:p-4',
                                        'border-accent/40 bg-accent-soft' => $active,
                                        'border-hairline bg-surface hover:-translate-y-0.5 hover:border-hairline-strong hover:elevate-lg' => ! $active,
                                    ])>
                                    <span @class(['grid h-10 w-10 place-items-center rounded-xl transition', 'bg-accent text-on-accent' => $active, 'bg-sunken text-ink-soft group-hover:bg-accent-soft group-hover:text-accent' => ! $active])>
                                        <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-5 w-5" />
                                    </span>
                                    <span class="mt-3 flex items-end justify-between gap-2">
                                        <span @class(['text-sm font-semibold leading-tight', 'text-accent-ink' => $active, 'text-ink' => ! $active])>{{ $item['label'] }}</span>
                                        <x-heroicon-o-arrow-up-right class="h-4 w-4 shrink-0 text-ink-muted opacity-0 transition group-hover:opacity-100" />
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>

        <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-hairline pt-4">
            <div class="min-w-0 text-sm">
                @if ($account)
                    <p class="truncate font-medium text-ink">{{ $account->name }}</p>
                    <p class="truncate text-xs text-ink-muted">{{ $roleLabel }}</p>
                @endif
            </div>
            <div class="flex items-center gap-1">
                @unless ($isPlatform)
                    <a href="{{ route('tenant.me.navigation') }}" wire:navigate x-on:click="$dispatch('close-launcher')" title="Arrange my navigation" aria-label="Arrange my navigation"
                        class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
                        <x-heroicon-o-adjustments-horizontal class="h-[18px] w-[18px]" />
                    </a>
                @endunless
                <x-ui.nav-style-toggle label />
                <x-ui.theme-toggle />
            </div>
        </div>
    </div>
</div>
