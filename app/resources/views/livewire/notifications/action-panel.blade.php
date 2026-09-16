<div>
    @if ($notification)
        {{-- Freshly shown after an action further down the page, the panel
             brings itself into view: on a phone it would otherwise sit above
             the fold, unseen. Nothing moves when it is already visible. --}}
        <div x-data x-init="$nextTick(() => {
                const rect = $el.getBoundingClientRect();
                if (rect.top < 0 || rect.bottom > window.innerHeight) {
                    $el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })" class="scroll-mt-20" wire:key="panel-scroll-{{ $notification->id }}"></div>
        {{--
            Editing and the deep link both live in Alpine so the URL always
            reflects what is on screen right now. Building the href server-side
            meant the link carried the text from before the last keystroke.
        --}}
        <x-ui.card>
            {{--
                copy() goes through the window helper rather than the clipboard
                API directly: that API does not exist outside a secure context,
                so on plain HTTP the button threw and did nothing.

                The Alpine scope lives on a plain element, not on <x-ui.card>:
                Blade does not compile @js() inside an x-component tag's
                attribute, so the directive reached the browser as literal text
                and every expression on the card failed with "copied is not
                defined".
            --}}
            <div
                x-data="{
                message: @js($message),
                digits: @js($recipientDigits),
                editing: false,
                copied: false,
                // 'idle' | 'saving' | 'saved': every edit is written as it is
                // typed, so refreshing or navigating away mid-edit loses nothing.
                saveState: 'idle',
                async persist() {
                    this.saveState = 'saving';
                    await this.$wire.saveMessage(this.message);
                    this.saveState = 'saved';
                    setTimeout(() => this.saveState = 'idle', 2000);
                },
                get href() {
                    return this.digits
                        ? `https://wa.me/${this.digits}?text=${encodeURIComponent(this.message)}`
                        : null;
                },
                copyFailed: false,
                async copy() {
                    if (await window.copyToClipboard(this.message)) {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);

                        return;
                    }

                    // Nothing automatic worked, so open the editor with the text
                    // selected — the operator can still copy it by hand.
                    this.copyFailed = true;
                    this.editing = true;

                    this.$nextTick(() => {
                        const field = this.$refs.editor;

                        field?.focus();
                        field?.select();
                    });
                },
                // Leaving the editor commits the edit, so the message list and
                // this panel can never show different wording.
                toggleEdit() {
                    if (this.editing) {
                        this.persist();
                    }

                    this.editing = ! this.editing;
                },
            }">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="flex items-start gap-2.5">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-positive-soft text-positive">
                        <x-heroicon-o-chat-bubble-left-right class="h-4 w-4" />
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-ink">
                            Notify {{ $notification->recipient_name }}
                        </p>
                        <p class="numeric truncate text-xs text-ink-muted">
                            {{ $displayPhone ?? 'No usable WhatsApp number on file' }}
                            @if ($context === 'queue')
                                &middot; queued {{ $notification->created_at?->diffForHumans() }}
                                @if ($notification->createdBy?->user)
                                    by {{ $notification->createdBy->user->name }}
                                @endif
                            @endif
                        </p>
                        @if ($notification->opened_at)
                            <p class="truncate text-xs text-positive">
                                Sent {{ $notification->opened_at->diffForHumans() }}
                                @if ($notification->openedBy?->user)
                                    by {{ $notification->openedBy->user->name }}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex min-w-0 flex-wrap items-center gap-1.5">
                    <x-ui.badge tone="neutral" :dot="false">{{ $notification->action_type->label() }}</x-ui.badge>
                    <x-ui.badge :tone="$notification->status->tone()">{{ $notification->status->label() }}</x-ui.badge>
                </div>
            </div>

            <div class="mt-3">
                <label class="sr-only" for="wa-message-{{ $notification->id }}">Message to send</label>
                <textarea id="wa-message-{{ $notification->id }}" x-ref="editor" x-model="message" x-show="editing" x-cloak rows="8"
                    x-on:input.debounce.600ms="persist()" x-on:change="persist()"
                    class="w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 font-mono text-[13px] leading-relaxed text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25"></textarea>
                <p x-show="editing" x-cloak class="mt-1 text-xs text-ink-muted">
                    <span x-show="saveState === 'saving'">Saving…</span>
                    <span x-show="saveState === 'saved'" class="text-positive">Saved — this wording is what will be sent.</span>
                    <span x-show="saveState === 'idle'">Changes are saved as you type.</span>
                </p>

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
                        <x-icon.whatsapp class="h-4 w-4 shrink-0" />
                        Open WhatsApp
                    </a>

                    <span x-show="! digits" x-cloak
                        class="inline-flex items-center gap-1.5 rounded-md bg-caution-soft px-2 py-1 text-xs font-medium text-caution">
                        <x-heroicon-o-exclamation-triangle class="h-3.5 w-3.5 shrink-0" />
                        No usable WhatsApp number — copy the message instead
                    </span>

                    <x-ui.button type="button" size="md" icon="clipboard-document" x-on:click="copy()">
                        <span x-show="! copied">Copy message</span>
                        <span x-show="copied" x-cloak class="text-positive">Copied</span>
                    </x-ui.button>

                    <span x-show="copyFailed" x-cloak
                        class="inline-flex items-center gap-1.5 rounded-md bg-caution-soft px-2 py-1 text-xs font-medium text-caution">
                        <x-heroicon-o-exclamation-triangle class="h-3.5 w-3.5 shrink-0" />
                        Your browser blocked copying — the message is selected, press Ctrl/Cmd+C
                    </span>

                    {{-- Only a message still to be sent can be reworded: once it
                         has gone out, the snapshot is the record of what was sent. --}}
                    @if ($notification->status === \App\Enums\NotificationStatus::Ready)
                        <x-ui.button type="button" size="md" variant="ghost" icon="pencil-square"
                            x-on:click="toggleEdit()">
                            {{-- Server-rendered fallback so the label is never blank
                                 in the moment before Alpine hydrates. --}}
                            <span x-text="editing ? 'Preview' : 'Edit message'">Edit message</span>
                        </x-ui.button>
                    @else
                        <span class="text-xs text-ink-muted">Already {{ strtolower($notification->status->label()) }} — the wording is fixed.</span>
                    @endif

                    @if ($context === 'queue' && $notification->status === \App\Enums\NotificationStatus::Ready)
                        {{-- In the queue, skipping is a decision not to send: it
                             takes the message out of the outstanding list. Only
                             offered while it is still outstanding. --}}
                        <x-ui.button type="button" size="md" variant="ghost" icon="x-mark" wire:click="skip"
                            data-confirm-title="Skip this message?" data-confirm-action="Skip" data-confirm-tone="danger" data-confirm="Skip this message? It comes off the list of messages still to send.">Skip</x-ui.button>
                    @elseif ($context !== 'queue')
                        {{-- Straight after an action, closing is just moving on.
                             The message stays in Messages to send later. --}}
                        <x-ui.button type="button" size="md" variant="ghost" icon="x-mark" wire:click="dismiss">Close</x-ui.button>
                    @endif
                </div>

                <p class="mt-2 text-xs text-ink-muted">
                    @if ($context === 'queue')
                        Opening WhatsApp marks this as sent and takes it off the outstanding list.
                    @else
                        Opening WhatsApp marks this as sent. Closing leaves it in
                        <a href="{{ route('tenant.notifications.index') }}" wire:navigate
                            class="underline underline-offset-2 hover:text-ink">Messages</a> to send later.
                    @endif
                    Either way, "sent" records that you launched WhatsApp — not that it was delivered or read.
                </p>
            </div>
            </div>
        </x-ui.card>
    @endif
</div>
