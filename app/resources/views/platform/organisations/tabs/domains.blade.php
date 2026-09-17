    <x-ui.card title="Domains" description="A hostname can belong to only one organisation.">
        <div class="space-y-2">
            @foreach ($organisation->domains as $domain)
                <div class="rounded-lg border border-hairline bg-raised p-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-mono text-sm text-ink">{{ $domain->hostname }}</span>
                        @if ($domain->is_primary)
                            <x-ui.badge tone="accent" :dot="false">Primary</x-ui.badge>
                        @endif
                        <x-ui.badge :tone="$domain->status->tone()">{{ $domain->status->label() }}</x-ui.badge>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <form method="POST" action="{{ route('platform.organisations.domains.update', [$organisation, $domain]) }}"
                            class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')

                            <select name="status" onchange="this.form.requestSubmit()" aria-label="Domain status"
                                class="min-h-[36px] rounded-lg border border-hairline-strong bg-surface px-2 text-xs text-ink-soft focus:border-accent focus:outline-none">
                                @foreach (\App\Enums\DomainStatus::cases() as $case)
                                    <option value="{{ $case->value }}" @selected($domain->status->value === $case->value)>
                                        {{ $case->label() }}
                                    </option>
                                @endforeach
                            </select>

                            @unless ($domain->is_primary)
                                <input type="hidden" name="is_primary" value="0">
                                <x-ui.button type="submit" name="is_primary" value="1" size="sm">Make primary</x-ui.button>
                            @endunless
                        </form>

                        @if ($organisation->domains->count() > 1)
                            <form method="POST" action="{{ route('platform.organisations.domains.destroy', [$organisation, $domain]) }}">
                                @csrf
                                @method('DELETE')
                                {{-- The confirmation is on the button, not the form: the
                                     interceptor replays the click, which submits it. --}}
                                <x-ui.button type="submit" size="sm" variant="danger"
                                    data-confirm-title="Remove this domain?"
                                    data-confirm-action="Remove domain"
                                    data-confirm="{{ $domain->hostname }} will stop working for sign-in immediately.">Remove</x-ui.button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('platform.organisations.domains.store', $organisation) }}"
            class="mt-4 space-y-3 border-t border-hairline pt-4">
            @csrf
            <x-ui.input name="hostname" id="new_hostname" label="Add domain" placeholder="club.example.com" />

            <x-ui.select name="status" id="new_status" label="Status">
                @foreach (\App\Enums\DomainStatus::cases() as $case)
                    <option value="{{ $case->value }}" @selected($case === \App\Enums\DomainStatus::Active)>{{ $case->label() }}</option>
                @endforeach
            </x-ui.select>

            <x-ui.button type="submit" variant="primary" class="w-full">Add domain</x-ui.button>
        </form>
    </x-ui.card>
