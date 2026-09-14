<div>
    <x-ui.flash />

    <x-ui.page-header title="Messages"
        description="Messages the platform has composed for WhatsApp. Nothing is sent automatically — an operator has to open each one." />

    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-3">
        <x-ui.stat label="Waiting to send" :value="$readyCount" icon="paper-airplane"
            :tone="$readyCount > 0 ? 'caution' : 'positive'" hint="not yet opened" />
        <x-ui.stat label="Sent" :value="$openedCount" icon="check" tone="positive" hint="WhatsApp opened" />
        <x-ui.stat label="No usable number" :value="$unavailableCount" icon="exclamation-triangle"
            :tone="$unavailableCount > 0 ? 'critical' : 'neutral'" hint="copy manually" />
    </div>

    <x-ui.card :padded="false">
        <x-ui.filters search="search" placeholder="Search by name or number…">
            <x-ui.filter-select wire:model.live="status" label="Status">
                <option value="">All pending</option>
                @foreach ($statuses as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>

            <x-ui.filter-select wire:model.live="action" label="Message type">
                <option value="">All types</option>
                @foreach ($actionTypes as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-ui.filter-select>
        </x-ui.filters>

        <div wire:loading.delay class="w-full"><x-ui.skeleton :rows="6" /></div>

        <div wire:loading.remove>
            @if ($messages->isEmpty())
                <x-ui.empty icon="chat-bubble-left-right"
                    :title="$search !== '' || $status !== '' || $action !== '' ? 'Nothing matches those filters' : 'No messages yet'"
                    :description="$search !== '' || $status !== '' || $action !== '' ? 'Try a different search or clear the filters.' : 'Messages appear here as you add members, confirm payments, and update staff.'" />
            @else
                {{-- The same block shown after an action, one per message. An
                     operator working through the backlog does exactly what they
                     do after an event, so the controls are the same controls
                     rather than a second, list-only design to learn. --}}
                <div class="space-y-3 p-4">
                    @foreach ($messages as $message)
                        <livewire:notifications.action-panel :notification-id="$message->id"
                            context="queue" :key="'queue-'.$message->id" />
                    @endforeach

                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </x-ui.card>
</div>
