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
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

            <div x-show="drawer"
                x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="-translate-x-full"
                x-transition:leave="transition duration-150 ease-in" x-transition:leave-end="-translate-x-full"
                class="absolute inset-y-0 left-0 flex w-72 max-w-[85vw] flex-col border-r border-hairline bg-surface">
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
            <header class="sticky top-0 z-20 flex h-14 items-center gap-2 border-b border-hairline bg-surface/85 px-3 backdrop-blur-md sm:px-6">
                {{-- Side-menu style: the hamburger opens the drawer on phones.
                     Launcher style: it opens the full-screen menu everywhere. --}}
                <button type="button" @click="document.documentElement.dataset.nav === 'launcher' ? $dispatch('open-launcher') : drawer = true" aria-label="Open navigation"
                    class="nav-hamburger -ml-1 grid h-11 w-11 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink lg:hidden">
                    <x-heroicon-o-bars-3 class="h-5.5 w-5.5" />
                </button>

                <span class="nav-brand-inline truncate font-[family-name:var(--font-display)] text-sm font-semibold lg:hidden">
                    {{ $brandName }}
                </span>

                {{-- Launcher style only: the Menu button on wider screens. --}}
                <button type="button" @click="$dispatch('open-launcher')"
                    class="nav-launcher-button hidden items-center gap-2 rounded-lg border border-hairline bg-surface px-3 py-1.5 text-sm font-medium text-ink-soft transition hover:bg-sunken hover:text-ink">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                    Menu
                </button>

                <div class="flex-1"></div>

                @isset($toolbar)
                    {{ $toolbar }}
                @endisset

                <x-ui.theme-toggle />
            </header>

            {{-- Screen readers get every flash message announced (MEP 9.3). --}}
            <div aria-live="polite" aria-atomic="true" class="sr-only">{{ session('status') }}</div>

            <main class="mx-auto w-full max-w-[1400px] px-4 pb-24 pt-6 sm:px-6 lg:pb-10">
                {{ $slot }}
            </main>
        </div>

        {{-- ===== Mobile bottom tabs ===== --}}
        @if ($mobileItems !== [])
            <nav class="fixed inset-x-0 bottom-0 z-30 grid grid-cols-5 border-t border-hairline bg-surface/95 pb-[env(safe-area-inset-bottom)] backdrop-blur-md lg:hidden"
                aria-label="Primary">
                @foreach ($mobileItems as $item)
                    @php $active = request()->routeIs($item['active']); @endphp
                    <a href="{{ route($item['route']) }}" wire:navigate.hover
                        x-data="{ busy: false }" x-on:click="busy = true"
                        x-on:livewire:navigated.window="busy = false" x-on:livewire:navigate-failed.window="busy = false"
                        @class([
                        'flex min-h-[56px] flex-col items-center justify-center gap-1 text-[11px] font-medium transition',
                        'text-accent' => $active,
                        'text-ink-muted' => ! $active,
                    ]) @if ($active) aria-current="page" @endif>
                        <span class="relative h-5 w-5">
                            <x-dynamic-component :component="'heroicon-o-'.$item['icon']" x-show="! busy" class="h-5 w-5" />
                            <span x-show="busy" x-cloak class="absolute inset-0 grid place-items-center text-accent"><x-ui.spinner size="xs" /></span>
                        </span>
                        <span class="max-w-full truncate px-1">{{ $item['label'] }}</span>
                    </a>
                @endforeach

                <button type="button" @click="drawer = true"
                    class="flex min-h-[56px] flex-col items-center justify-center gap-1 text-[11px] font-medium text-ink-muted transition">
                    <x-heroicon-o-ellipsis-horizontal-circle class="h-5 w-5" />
                    <span>More</span>
                </button>
            </nav>
        @endif
    </div>

    @if (! $isPlatform && $membership)
        {{-- Due task reminders, shown once per page load. --}}
        <livewire:tasks.reminders />
    @endif

    <x-nav.launcher :sections="$sections" :brand-name="$brandName" :account="$account" :role-label="$roleLabel" :is-platform="$isPlatform" />
    <x-ui.confirm-dialog />

    @livewireScripts
</body>
</html>
