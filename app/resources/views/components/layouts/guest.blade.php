@php
    $tenantOrg = $tenant ?? null;
    $accent = $tenantOrg?->themeCss();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($heading ?? config('app.name')) }}</title>
    @if ($tenantOrg)
        {{-- Branded even before sign-in: the login page is the first thing a
             member sees, and it is also a page Chrome can offer to install
             from, so it carries the manifest as well as the icons. --}}
        <link rel="icon" href="{{ $tenantOrg->tabIconUrl() }}">
        <link rel="apple-touch-icon" href="{{ route('tenant.branding.app-icon', ['size' => 192]) }}">
        <link rel="manifest" href="{{ route('tenant.manifest') }}">
        <meta name="theme-color" content="{{ $tenantOrg->brandColor() }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <x-install-script />
    @endif

    <x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- After the stylesheet so the organisation's accent wins on equal
         specificity. Only the accent tokens are overridden: the semantic
         positive/caution/critical colours carry meaning and stay fixed. --}}
    @if ($accent)
        <style>{!! $accent !!}</style>
    @endif
</head>
<body class="min-h-screen bg-app font-sans text-ink antialiased">
    <div class="flex min-h-screen flex-col">
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6">
            <div class="w-full max-w-sm">
                <div class="mb-6 text-center">
                    @if ($tenantOrg?->logoUrl())
                        <img src="{{ $tenant->logoUrl() }}" alt="{{ $tenant->name }}"
                            class="mx-auto mb-3 h-11 w-11 rounded-xl bg-sunken object-contain p-1">
                    @else
                        <span class="mx-auto mb-3 grid h-11 w-11 place-items-center rounded-xl bg-accent font-[family-name:var(--font-display)] text-lg font-bold text-on-accent">
                            {{ mb_strtoupper(mb_substr($eyebrow ?? config('app.name'), 0, 1)) }}
                        </span>
                    @endif

                    <h1 class="font-[family-name:var(--font-display)] text-xl font-semibold tracking-tight">
                        {{ $eyebrow ?? config('app.name') }}
                    </h1>
                    <p class="mt-1 text-sm text-ink-muted">{{ $heading ?? 'Sign in' }}</p>
                </div>

                <div class="rounded-xl border border-hairline bg-surface p-5 elevate-lg sm:p-6">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-ink-muted">
                    &copy; {{ now()->year }} {{ config('app.name') }}
                </p>
            </div>
        </div>
    </div>
    <x-ui.page-loader />
    <x-ui.confirm-dialog />
</body>
</html>
