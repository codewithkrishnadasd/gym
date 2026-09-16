@php
    // Error pages can render before the tenant is resolved (an unknown
    // hostname, the platform console), so nothing here assumes one.
    $tenant = app()->bound('tenant') ? app('tenant') : null;
    $signedIn = auth('web')->check() || auth('platform')->check();
    $homeUrl = auth('platform')->check()
        ? route('platform.dashboard')
        : ($tenant ? route($signedIn ? 'tenant.dashboard' : 'tenant.login') : url('/'));
@endphp
<x-layouts.guest :tenant="$tenant" :title="$__env->yieldContent('title')" :eyebrow="$tenant?->name ?? config('app.name')" :heading="$__env->yieldContent('title')">
    <div class="text-center">
        <p class="numeric font-[family-name:var(--font-display)] text-5xl font-semibold tracking-tight text-ink-muted">@yield('code')</p>
        <h2 class="mt-3 text-lg font-semibold text-ink">@yield('title')</h2>
        <p class="mt-2 text-sm text-ink-soft">@yield('message')</p>

        <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:justify-center">
            <x-ui.button variant="primary" :href="$homeUrl">{{ $signedIn ? 'Go to the dashboard' : 'Go to sign in' }}</x-ui.button>
            <x-ui.button type="button" onclick="history.back()">Go back</x-ui.button>
        </div>

        @hasSection('detail')
            <p class="mt-6 text-xs text-ink-muted">@yield('detail')</p>
        @endif
    </div>
</x-layouts.guest>
