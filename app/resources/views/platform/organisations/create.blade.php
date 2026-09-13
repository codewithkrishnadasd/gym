<x-layouts.app heading="New organisation">
    <x-ui.page-header title="New organisation" :back="route('platform.organisations.index')" back-label="Organisations"
        description="Creates the organisation, its primary domain, and the first administrator in one step." />

    @if ($errors->any())
        <div class="mb-4">
            <x-ui.alert tone="critical" title="Please fix the following">
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        </div>
    @endif

    <form method="POST" action="{{ route('platform.organisations.store') }}" class="grid max-w-4xl gap-5 lg:grid-cols-3">
        @csrf

        <div class="space-y-5 lg:col-span-2">
            <x-ui.card title="Organisation">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input name="name" id="name" label="Name" value="{{ old('name') }}" required placeholder="FitZone" />
                    <x-ui.input name="slug" id="slug" label="Slug" value="{{ old('slug') }}" required placeholder="fitzone"
                        hint="Lowercase identifier, unique across the platform." />

                    <x-ui.input class="sm:col-span-2" name="hostname" id="hostname" label="Primary domain"
                        value="{{ old('hostname') }}" required placeholder="fitzone.example.com"
                        hint="Where staff at this organisation will sign in." />
                </div>
            </x-ui.card>

            <x-ui.card title="First administrator"
                description="This person can then invite everyone else and configure the organisation.">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input name="admin_name" id="admin_name" label="Name" value="{{ old('admin_name') }}" required />
                    <x-ui.input name="admin_email" id="admin_email" label="Email" type="email" value="{{ old('admin_email') }}" required />

                    <x-ui.input class="sm:col-span-2" name="admin_password" id="admin_password" label="Password" type="password"
                        hint="Leave blank if this email already has an account elsewhere on the platform — they will sign in with their existing password." />
                </div>
            </x-ui.card>
        </div>

        <div>
            <x-ui.card title="Create">
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" variant="primary" size="lg">Create organisation</x-ui.button>
                    <x-ui.button variant="ghost" :href="route('platform.organisations.index')">Cancel</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </form>
</x-layouts.app>
