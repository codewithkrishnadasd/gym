    <x-ui.card title="People" description="Accounts with a membership in this organisation.">
        <div class="grid gap-2 sm:grid-cols-2">
            @forelse ($organisation->organisationUsers as $membership)
                <div class="rounded-lg border border-hairline bg-raised p-3">
                    <div class="flex items-start gap-2.5">
                        <x-ui.avatar :name="$membership->user->name" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-ink">{{ $membership->user->name }}</p>
                            <p class="numeric truncate text-xs text-ink-muted">{{ $membership->user->phone }}</p>
                        </div>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-1.5">
                        <x-ui.badge :tone="$membership->role->value === 'admin' ? 'accent' : 'neutral'" :dot="false">
                            {{ $membership->role->value === 'admin' ? 'Administrator' : 'Staff' }}
                        </x-ui.badge>
                        <x-ui.badge :tone="$membership->status->tone()">{{ $membership->status->label() }}</x-ui.badge>
                    </div>

                    <form method="POST" action="{{ route('platform.organisations.members.reset-password', [$organisation, $membership]) }}"
                        class="mt-2">
                        @csrf
                        <x-ui.button type="submit" size="sm" icon="key" class="w-full"
                            data-confirm-title="Create a password link?"
                            data-confirm-action="Create link"
                            data-confirm-tone="accent"
                            data-confirm="Any earlier link for {{ $membership->user->name }} stops working. Their current password keeps working until they use this one.">Create password link</x-ui.button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-ink-muted">No people yet.</p>
            @endforelse
        </div>
    </x-ui.card>
