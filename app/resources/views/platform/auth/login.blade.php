<x-layouts.guest eyebrow="Platform" heading="Root console sign in">
    @if ($errors->any())
        <div class="mb-4">
            <x-ui.alert tone="critical">{{ $errors->first() }}</x-ui.alert>
        </div>
    @endif

    <form method="POST" action="{{ route('platform.login.attempt') }}" class="space-y-4">
        @csrf

        <x-ui.input name="phone" id="phone" label="WhatsApp number" type="tel" value="{{ old('phone') }}" required
            autofocus autocomplete="username" inputmode="tel" placeholder="98765 43210" />

        <x-ui.input name="password" id="password" label="Password" type="password" required
            autocomplete="current-password" />

        <x-ui.checkbox name="remember" value="1" label="Keep me signed in" />

        <x-ui.button type="submit" variant="primary" size="lg" class="w-full">Sign in</x-ui.button>
    </form>
</x-layouts.guest>
