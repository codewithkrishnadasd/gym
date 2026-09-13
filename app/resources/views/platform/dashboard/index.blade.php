<x-layouts.app heading="Overview">
    <x-ui.flash />

    <x-ui.page-header title="Platform overview" description="Organisations, domains, and tenant health.">
        <x-slot:actions>
            <x-ui.button variant="primary" icon="plus" :href="route('platform.organisations.create')">New organisation</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        <x-ui.stat label="Organisations" :value="$organisationCount" icon="building-office-2" tone="accent"
            :href="route('platform.organisations.index')" hint="on the platform" />

        <x-ui.card title="Signed in as">
            <div class="flex items-center gap-3">
                <x-ui.avatar :name="$admin->name" size="lg" tone="accent" />
                <div class="min-w-0">
                    <p class="truncate font-medium text-ink">{{ $admin->name }}</p>
                    <p class="numeric truncate text-sm text-ink-muted">{{ $admin->phone }}</p>
                </div>
            </div>
        </x-ui.card>
    </div>
</x-layouts.app>
