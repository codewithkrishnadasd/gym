<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($heading ?? config('app.name')) }}</title>

    {{-- Matches the app shell: theme resolved before first paint. --}}
    <script>
        (() => {
            const stored = localStorage.getItem('theme');
            const system = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.dataset.theme = stored ?? system;
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-app font-sans text-ink antialiased">
    <div class="flex min-h-screen flex-col">
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6">
            <div class="w-full max-w-sm">
                <div class="mb-6 text-center">
                    <span class="mx-auto mb-3 grid h-11 w-11 place-items-center rounded-xl bg-accent font-[family-name:var(--font-display)] text-lg font-bold text-on-accent">
                        {{ mb_strtoupper(mb_substr($eyebrow ?? config('app.name'), 0, 1)) }}
                    </span>

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
</body>
</html>
