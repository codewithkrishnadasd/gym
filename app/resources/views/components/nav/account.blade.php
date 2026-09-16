@props(['account', 'roleLabel', 'isPlatform' => false])

<div class="shrink-0 border-t border-hairline p-3" x-data="{ open: false }" @click.outside="open = false">
    <div x-cloak x-show="open" x-transition class="mb-1 overflow-hidden rounded-lg border border-hairline bg-raised">
        {{-- Appearance lives here as options rather than in a top bar — on a
             phone there is no top bar at all. --}}
        <x-ui.theme-toggle label class="w-full justify-start rounded-none px-3 py-2.5 font-medium" />
        <x-ui.nav-style-toggle label class="w-full justify-start rounded-none px-3 py-2.5 font-medium" />
        <div class="border-t border-hairline"></div>
        <form method="POST" action="{{ $isPlatform ? route('platform.logout') : route('tenant.logout') }}">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-2.5 px-3 py-2.5 text-sm font-medium text-ink-soft transition hover:bg-sunken hover:text-critical">
                <x-heroicon-o-arrow-right-start-on-rectangle class="h-[18px] w-[18px]" />
                Sign out
            </button>
        </form>
    </div>

    <button type="button" @click="open = ! open" :aria-expanded="open"
        class="flex w-full items-center gap-2.5 rounded-lg px-2 py-2 text-left transition hover:bg-sunken">
        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-sunken text-xs font-semibold text-ink-soft">
            {{ mb_strtoupper(mb_substr($account?->name ?? '?', 0, 1)) }}
        </span>
        <span class="min-w-0 flex-1">
            <span class="block truncate text-sm font-medium leading-tight">{{ $account?->name }}</span>
            <span class="block truncate text-xs text-ink-muted">{{ $roleLabel }}</span>
        </span>
        <x-heroicon-o-chevron-up-down class="h-4 w-4 shrink-0 text-ink-muted" />
    </button>
</div>
