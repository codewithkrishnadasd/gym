<div>
    @if ($notification)
        {{--
            Editing and the deep link both live in Alpine so the URL always
            reflects what is on screen right now. Building the href server-side
            meant the link carried the text from before the last keystroke.
        --}}
        <x-ui.card
            x-data="{
                message: @js($message),
                digits: @js($recipientDigits),
                editing: false,
                copied: false,
                get href() {
                    return this.digits
                        ? `https://wa.me/${this.digits}?text=${encodeURIComponent(this.message)}`
                        : null;
                },
                copy() {
                    navigator.clipboard.writeText(this.message).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                },
            }">
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

            <div class="mt-3">
                <label class="sr-only" for="wa-message-{{ $notification->id }}">Message to send</label>
                <textarea id="wa-message-{{ $notification->id }}" x-model="message" x-show="editing" x-cloak rows="8"
                    class="w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 font-mono text-[13px] leading-relaxed text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25"></textarea>

                <pre x-show="! editing"
                    class="max-h-56 overflow-y-auto whitespace-pre-wrap rounded-lg border border-hairline bg-raised px-3 py-2.5 font-sans text-[13px] leading-relaxed text-ink-soft"
                    x-text="message">{{ $message }}</pre>

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    {{-- A real anchor opened by the operator's own click, so a
                         popup blocker can never swallow the action. --}}
                    <a x-show="digits" :href="href" target="_blank" rel="noopener"
                        href="{{ $recipientDigits ? 'https://wa.me/'.$recipientDigits.'?text='.rawurlencode($message) : '#' }}"
                        x-on:click="$wire.markOpened(message)"
                        class="inline-flex min-h-[38px] items-center justify-center gap-1.5 rounded-lg bg-positive px-3.5 py-2 text-sm font-medium text-white transition hover:opacity-90 max-lg:min-h-[44px]">
                        <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                        Open WhatsApp
                    </a>

                    <template x-if="! digits">
                        <x-ui.badge tone="caution">WhatsApp unavailable — copy the message instead</x-ui.badge>
                    </template>

                    <x-ui.button type="button" size="md" icon="clipboard-document" x-on:click="copy()">
                        <span x-show="! copied">Copy message</span>
                        <span x-show="copied" x-cloak class="text-positive">Copied</span>
                    </x-ui.button>

                    <x-ui.button type="button" size="md" variant="ghost" x-on:click="editing = ! editing">
                        {{-- Server-rendered fallback so the label is never blank
                             in the moment before Alpine hydrates. --}}
                        <span x-text="editing ? 'Preview' : 'Edit message'">Edit message</span>
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
