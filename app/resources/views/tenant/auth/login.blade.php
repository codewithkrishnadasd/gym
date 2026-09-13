<x-layouts.guest :eyebrow="$tenant->name" heading="Sign in to continue">
    @if ($errors->any())
        <div class="mb-4">
            <x-ui.alert tone="critical">{{ $errors->first() }}</x-ui.alert>
        </div>
    @endif

    <form method="POST" action="{{ route('tenant.login.attempt') }}" class="space-y-4">
        @csrf

        <x-ui.input name="email" id="email" label="Email" type="email" value="{{ old('email') }}" required autofocus
            autocomplete="username" placeholder="you@example.com" />

        <x-ui.input name="password" id="password" label="Password" type="password" required
            autocomplete="current-password" />

        <x-ui.checkbox name="remember" value="1" label="Keep me signed in" />

        <x-ui.button type="submit" variant="primary" size="lg" class="w-full">Sign in</x-ui.button>
    </form>
</x-layouts.guest>
