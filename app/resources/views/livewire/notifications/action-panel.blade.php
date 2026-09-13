<div>
    @if ($notification)
        <x-ui.card>
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="flex items-start gap-2.5">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-positive-soft text-positive">
                        <x-heroicon-o-chat-bubble-left-right class="h-4 w-4" />
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-ink">Notify {{ $notification->recipient_name }}</p>
                        <p class="text-xs text-ink-muted">
                            {{ $displayPhone ?? 'No usable WhatsApp number on file' }}
                        </p>
                    </div>
                </div>

                <x-ui.badge :tone="$notification->status->tone()">{{ $notification->status->label() }}</x-ui.badge>
            </div>

            <div class="mt-3" x-data="{ copied: false }">
                @if ($editing)
                    <textarea wire:model="draft" rows="8"
                        class="w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 font-mono text-[13px] leading-relaxed text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25"></textarea>
                @else
                    <pre class="max-h-56 overflow-y-auto whitespace-pre-wrap rounded-lg border border-hairline bg-raised px-3 py-2.5 font-sans text-[13px] leading-relaxed text-ink-soft">{{ $message }}</pre>
                @endif

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    @if ($whatsappUrl)
                        {{-- A real anchor, opened by the admin's own click, so a
                             popup blocker can never swallow the action. --}}
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                            wire:click="markOpened"
                            class="inline-flex min-h-[38px] items-center justify-center gap-1.5 rounded-lg bg-positive px-3.5 py-2 text-sm font-medium text-white transition hover:opacity-90 max-lg:min-h-[44px]">
                            <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                            Open WhatsApp
                        </a>
                    @else
                        <x-ui.badge tone="caution">WhatsApp unavailable — copy the message instead</x-ui.badge>
                    @endif

                    <x-ui.button type="button" size="md" icon="clipboard-document"
                        x-on:click="navigator.clipboard.writeText(@js($message)).then(() => { copied = true; setTimeout(() => copied = false, 2000) })">
                        <span x-show="! copied">Copy message</span>
                        <span x-show="copied" x-cloak class="text-positive">Copied</span>
                    </x-ui.button>

                    <x-ui.button type="button" size="md" variant="ghost" wire:click="$toggle('editing')">
                        {{ $editing ? 'Preview' : 'Edit message' }}
                    </x-ui.button>

                    <x-ui.button type="button" size="md" variant="ghost" wire:click="skip">Skip</x-ui.button>
                </div>

                <p class="mt-2 text-xs text-ink-muted">
                    Opening WhatsApp records that the link was launched. It is not confirmation that the message was
                    delivered or read.
                </p>
            </div>
        </x-ui.card>
    @endif
</div>
