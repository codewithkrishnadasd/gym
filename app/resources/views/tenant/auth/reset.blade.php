<x-layouts.guest :eyebrow="$tenant->name" heading="Choose a password">
    @if ($errors->any())
        <div class="mb-4">
            <x-ui.alert tone="critical">{{ $errors->first() }}</x-ui.alert>
        </div>
    @endif

    <p class="mb-4 text-sm text-ink-soft">
        Hi {{ $name }} — set a password for your account. You will be signed in straight away.
    </p>

    <form method="POST" action="{{ route('tenant.password.set.store', $token) }}" class="space-y-4">
        @csrf

        <x-ui.input name="password" id="password" label="New password" type="password" required autofocus
            autocomplete="new-password" hint="At least 8 characters." />

        <x-ui.input name="password_confirmation" id="password_confirmation" label="Confirm password" type="password"
            required autocomplete="new-password" />

        <x-ui.button type="submit" variant="primary" size="lg" class="w-full">Set password and sign in</x-ui.button>
    </form>

    <p class="mt-4 text-center text-xs text-ink-muted">
        This link stops working at {{ $expiresAt->timezone($tenant->timezone)->format('g:ia') }}
        and can only be used once.
    </p>
</x-layouts.guest>
