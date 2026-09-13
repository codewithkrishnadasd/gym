<x-layouts.app heading="Organisations">
    <x-ui.flash />

    <x-ui.page-header title="Organisations"
        :description="$organisations->count().' '.Str::plural('organisation', $organisations->count()).' on the platform'">
        <x-slot:actions>
            <x-ui.button variant="primary" icon="plus" :href="route('platform.organisations.create')">New organisation</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card :padded="false">
        @if ($organisations->isEmpty())
            <x-ui.empty icon="building-office-2" title="No organisations yet"
                description="Create the first organisation and map a domain to it.">
                <x-slot:actions>
                    <x-ui.button variant="primary" icon="plus" :href="route('platform.organisations.create')">
                        Create the first one
                    </x-ui.button>
                </x-slot:actions>
            </x-ui.empty>
        @else
            <ul class="divide-y divide-[var(--c-hairline)]">
                @foreach ($organisations as $organisation)
                    <li class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-start gap-3">
                            <x-ui.avatar :name="$organisation->name" size="lg" tone="accent" />

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('platform.organisations.edit', $organisation) }}"
                                        class="font-medium text-ink hover:text-accent">{{ $organisation->name }}</a>
                                    <x-ui.badge :tone="$organisation->status->tone()">{{ $organisation->status->label() }}</x-ui.badge>
                                </div>

                                <p class="mt-0.5 truncate text-xs text-ink-muted">
                                    {{ $organisation->slug }} &middot; {{ $organisation->currency_code }} &middot; {{ $organisation->timezone }}
                                </p>

                                @if ($organisation->domains->isNotEmpty())
                                    <p class="mt-1 flex flex-wrap gap-1">
                                        @foreach ($organisation->domains as $domain)
                                            <span class="rounded bg-sunken px-1.5 py-0.5 font-mono text-[11px] text-ink-soft">
                                                {{ $domain->hostname }}
                                            </span>
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <x-ui.button size="sm" :href="route('platform.organisations.edit', $organisation)">Manage</x-ui.button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-ui.card>
</x-layouts.app>
