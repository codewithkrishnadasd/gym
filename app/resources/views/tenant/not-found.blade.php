{{-- Rendered without touching any tenant-scoped table, because no
     organisation could be resolved for this hostname (MEP.md 3.2). --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <title>Organisation not found</title>
    <x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-app font-sans text-ink antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12 text-center sm:px-6">
        <span class="grid h-12 w-12 place-items-center rounded-xl bg-critical-soft text-critical">
            <x-heroicon-o-exclamation-triangle class="h-6 w-6" />
        </span>

        <h1 class="mt-5 font-[family-name:var(--font-display)] text-xl font-semibold tracking-tight sm:text-2xl">
            Organisation not found
        </h1>

        <p class="mt-2 max-w-sm text-sm text-ink-soft">
            We couldn't find a gym or club for this web address. Check the link you used, or contact your
            organisation's administrator.
        </p>

        <p class="mt-6 text-xs text-ink-muted">{{ config('app.name') }}</p>
    </div>
</body>
</html>
