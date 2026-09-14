{{-- Not a 404: the person is holding a token, so hiding the reason only leaves
     them stuck. Naming the problem is what lets them ask for a new link. --}}
<x-layouts.guest :eyebrow="$tenant->name" heading="This link no longer works">
    <div class="mb-4">
        <x-ui.alert tone="caution" title="Link expired or already used">
            {{ $reason ?? 'Password links last a short time and work once. Ask whoever sent it to generate a new one.' }}
        </x-ui.alert>
    </div>

    <x-ui.button :href="route('tenant.login')" variant="primary" size="lg" class="w-full">Back to sign in</x-ui.button>
</x-layouts.guest>
