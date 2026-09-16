@php
    use App\Support\Navigation;

    $isPlatform = auth('platform')->check();
    $organisation = app()->bound('tenant') ? app('tenant') : null;
    $membership = app()->bound('membership') ? app('membership') : null;

    if (! $isPlatform && $organisation && ! $membership && auth('web')->check()) {
        $membership = auth('web')->user()->membershipFor($organisation);
    }

    $sections = $isPlatform
        ? Navigation::forPlatform()
        : ($organisation ? Navigation::forTenant($organisation, $membership) : []);

    $mobileItems = Navigation::mobilePrimary($sections);

    $brandName = $isPlatform ? 'Platform' : ($organisation->name ?? config('app.name'));
    $brandInitial = mb_strtoupper(mb_substr($brandName, 0, 1));
    $brandLogoUrl = $isPlatform ? null : $organisation?->logoUrl();
    $faviconUrl = $isPlatform ? null : $organisation?->tabIconUrl();
    $accent = $isPlatform ? null : $organisation?->themeCss();
    $pageTitle = $heading ?? ($isPlatform ? 'Root console' : 'Dashboard');

    $account = $isPlatform ? auth('platform')->user() : auth('web')->user();
    $roleLabel = $isPlatform
        ? 'Platform admin'
        : ($membership?->isAdmin() ? 'Administrator' : ($organisation?->term('user_singular') ?? 'Staff'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <title>{{ $pageTitle }} &middot; {{ $brandName }}</title>
    @if ($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    @unless ($isPlatform)
        {{-- What makes Chrome offer to install this as a desktop app. Only on
             tenant domains: the platform console is an operator tool, not
             something anyone installs. --}}
        <link rel="manifest" href="{{ route('tenant.manifest') }}">
        <meta name="theme-color" content="{{ $organisation?->brandColor() ?? \App\Support\Theme\AccentPalette::DEFAULT_ACCENT }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" href="{{ route('tenant.branding.app-icon', ['size' => 192]) }}">
        <x-install-script />
    @endunless

    <x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- After the stylesheet so the organisation's accent wins on equal
         specificity. Only the accent tokens are overridden: the semantic
         positive/caution/critical colours carry meaning and stay fixed. --}}
    @if ($accent)
        <style>{!! $accent !!}</style>
    @endif
    @livewireStyles
</head>
<body class="min-h-screen bg-app font-sans text-ink antialiased" data-no-progress-bar>
    <div x-data="{ drawer: false }" @keydown.escape.window="drawer = false">
        {{-- ===== Desktop sidebar ===== --}}
        <aside class="nav-sidebar fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-hairline bg-surface lg:flex">
            <x-nav.brand :name="$brandName" :initial="$brandInitial" :is-platform="$isPlatform" :logo-url="$brandLogoUrl" />

            <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4" aria-label="Main">
                @foreach ($sections as $section)
                    <div>
                        @if ($section['heading'])
                            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-ink-muted">
                                {{ $section['heading'] }}
                            </p>
                        @endif
                        <ul class="space-y-0.5">
                            @foreach ($section['items'] as $item)
                                <li><x-nav.item :item="$item" /></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>

            @unless ($isPlatform)
                <x-nav.install-app />
            @endunless

            <x-nav.account :account="$account" :role-label="$roleLabel" :is-platform="$isPlatform" />
        </aside>

        {{-- ===== Mobile drawer ===== --}}
        <div x-cloak x-show="drawer" class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true" aria-label="Navigation">
            <div x-show="drawer" x-transition.opacity @click="drawer = false"
                class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

            {{-- Full screen on a phone: one column of big targets, nothing
                 peeking out from behind to tap by mistake. --}}
            <div x-show="drawer"
                x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="-translate-x-full"
                x-transition:leave="transition duration-150 ease-in" x-transition:leave-end="-translate-x-full"
                class="absolute inset-0 flex w-full flex-col bg-surface sm:inset-y-0 sm:left-0 sm:right-auto sm:w-80 sm:border-r sm:border-hairline">
                <div class="flex items-center justify-between border-b border-hairline pr-2">
                    <x-nav.brand :name="$brandName" :initial="$brandInitial" :is-platform="$isPlatform" :logo-url="$brandLogoUrl" class="border-0" />
                    <button type="button" @click="drawer = false" aria-label="Close navigation"
                        class="grid h-11 w-11 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4" aria-label="Main">
                    @foreach ($sections as $section)
                        <div>
                            @if ($section['heading'])
                                <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-ink-muted">
                                    {{ $section['heading'] }}
                                </p>
                            @endif
                            <ul class="space-y-0.5">
                                @foreach ($section['items'] as $item)
                                    <li><x-nav.item :item="$item" mobile /></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </nav>

                @unless ($isPlatform)
                    <x-nav.install-app />
                @endunless

                <x-nav.account :account="$account" :role-label="$roleLabel" :is-platform="$isPlatform" />
            </div>
        </div>

        {{-- ===== Main column ===== --}}
        <div class="nav-shell lg:pl-64">
            {{-- Desktop only. On a phone the bottom bar is the navigation and
                 appearance options live in the menu, so the page starts at the top. --}}
            <header class="sticky top-0 z-20 hidden h-14 items-center gap-2 border-b border-hairline bg-surface/85 px-3 backdrop-blur-md sm:px-6 lg:flex">
                {{-- Side-menu style: the hamburger opens the drawer on phones.
                     Launcher style: it opens the full-screen menu everywhere. --}}
                <button type="button" @click="document.documentElement.dataset.nav === 'launcher' ? $dispatch('open-launcher') : drawer = true" aria-label="Open navigation"
                    class="nav-hamburger -ml-1 grid h-11 w-11 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink lg:hidden">
                    <x-heroicon-o-bars-3 class="h-5.5 w-5.5" />
                </button>

                <span class="nav-brand-inline truncate font-[family-name:var(--font-display)] text-sm font-semibold lg:hidden">
                    {{ $brandName }}
                </span>

                <div class="flex-1"></div>

                @isset($toolbar)
                    {{ $toolbar }}
                @endisset

                <x-ui.theme-toggle />

                {{-- Launcher style only: the Menu button, last in the bar. --}}
                <button type="button" @click="$dispatch('open-launcher')"
                    class="nav-launcher-button ml-1 hidden items-center gap-2 rounded-lg border border-hairline bg-surface px-3 py-1.5 text-sm font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                    Menu
                </button>
            </header>

            {{-- Screen readers get every flash message announced (MEP 9.3). --}}
            <div aria-live="polite" aria-atomic="true" class="sr-only">{{ session('status') }}</div>

            <main class="mx-auto w-full max-w-[1400px] px-4 pb-28 pt-6 sm:px-6 lg:pb-10">
                {{ $slot }}
            </main>
        </div>

        {{-- ===== Mobile bottom tabs ===== --}}
        @if ($mobileItems !== [])
            {{-- Floats clear of the screen edges as a frosted pill; the active
                 tab sits on its own soft accent pill. Kept above the home
                 indicator on phones with one. --}}
            <nav class="glass-nav fixed inset-x-3 bottom-[max(0.75rem,env(safe-area-inset-bottom))] z-30 grid grid-cols-5 rounded-[1.375rem] px-1 py-1 lg:hidden"
                aria-label="Primary">
                @foreach ($mobileItems as $item)
                    @php $active = request()->routeIs($item['active']); @endphp
                    <a href="{{ route($item['route']) }}" wire:navigate.hover
                        x-data="{ busy: false }" x-on:click="busy = true"
                        x-on:livewire:navigated.window="busy = false" x-on:livewire:navigate-failed.window="busy = false"
                        @class([
                        'flex min-h-[54px] flex-col items-center justify-center gap-0.5 rounded-2xl text-[11px] font-medium transition active:scale-95',
                        'text-accent-ink' => $active,
                        'text-ink-muted hover:text-ink' => ! $active,
                    ]) @if ($active) aria-current="page" @endif>
                        <span @class(['relative grid h-7 w-12 place-items-center rounded-full transition', 'bg-accent-soft' => $active])>
                            <x-dynamic-component :component="'heroicon-o-'.$item['icon']" x-show="! busy" class="h-5 w-5" />
                            <span x-show="busy" x-cloak class="absolute inset-0 grid place-items-center text-accent"><x-ui.spinner size="xs" /></span>
                        </span>
                        <span class="max-w-full truncate px-1">{{ $item['label'] }}</span>
                    </a>
                @endforeach

                {{-- Opens whichever menu the person chose: the side drawer, or
                     the full-screen launcher. --}}
                <button type="button" @click="document.documentElement.dataset.nav === 'launcher' ? $dispatch('open-launcher') : drawer = true"
                    class="flex min-h-[54px] flex-col items-center justify-center gap-0.5 rounded-2xl text-[11px] font-medium text-ink-muted transition hover:text-ink active:scale-95">
                    <span class="grid h-7 w-12 place-items-center rounded-full">
                        <x-heroicon-o-ellipsis-horizontal-circle class="h-5 w-5" />
                    </span>
                    <span>Menu</span>
                </button>
            </nav>
        @endif
    </div>

    @if (! $isPlatform && $membership && $organisation?->hasFeature(\App\Enums\Feature::Tasks))
        {{-- Due task reminders, shown once per page load. --}}
        <livewire:tasks.reminders />
    @endif

    <x-nav.launcher :sections="$sections" :brand-name="$brandName" :account="$account" :role-label="$roleLabel" :is-platform="$isPlatform" />
    <x-ui.confirm-dialog />

    @livewireScripts
</body>
</html>
