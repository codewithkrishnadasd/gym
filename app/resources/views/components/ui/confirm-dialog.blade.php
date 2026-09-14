{{--
    One confirmation dialog per page, driven by `data-confirm` on any button,
    link, or submit control. See the interceptor in resources/js/app.js.

    Replaces window.confirm(), which cannot be styled, ignores the dark theme,
    renders as a cramped strip at the top of a phone screen, and on some mobile
    browsers offers a "prevent this page from creating more dialogs" checkbox
    that silently disables every later confirmation.

    Marked `role="alertdialog"` rather than `dialog`: it interrupts to ask
    something that must be answered before anything else happens, which is what
    screen readers announce differently.
--}}
<div
    data-confirm-dialog
    x-data="{
        open: false,
        title: '',
        message: '',
        action: '',
        tone: 'danger',
        accept: null,
        show(detail) {
            this.title = detail.title;
            this.message = detail.message;
            this.action = detail.action;
            this.tone = detail.tone;
            this.accept = detail.accept;
            this.open = true;

            // Focus lands on the confirm button so Enter completes the action
            // and Escape cancels — the same two keys window.confirm() offers.
            this.$nextTick(() => this.$refs.confirm?.focus());
        },
        cancel() {
            this.open = false;
            this.accept = null;
        },
        proceed() {
            const run = this.accept;

            this.open = false;
            this.accept = null;

            run?.();
        },
    }"
    x-on:confirm-request.window="show($event.detail)"
    x-on:keydown.escape.window="if (open) cancel()"
    x-cloak
    x-show="open"
    class="fixed inset-0 z-[60] overflow-y-auto"
    role="alertdialog"
    aria-modal="true"
    :aria-label="title">

    <div x-show="open" x-transition.opacity x-on:click="cancel()"
        class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

    {{-- Bottom sheet on a phone, centred card from `sm` up: a thumb reaches the
         bottom of a screen far more easily than the middle of one. --}}
    <div class="flex min-h-full items-end justify-center p-0 sm:items-center sm:p-4">
        <div
            x-show="open"
            x-trap.noscroll="open"
            x-transition:enter="transition duration-150 ease-out"
            x-transition:enter-start="translate-y-6 opacity-0 sm:translate-y-0 sm:scale-95"
            x-transition:leave="transition duration-100 ease-in"
            x-transition:leave-end="opacity-0 sm:scale-95"
            class="relative w-full max-w-md rounded-t-2xl border border-hairline bg-surface pb-[env(safe-area-inset-bottom)] elevate-lg sm:rounded-xl sm:pb-0">

            <div class="flex items-start gap-3 px-5 pt-5">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full"
                    :class="tone === 'danger' ? 'bg-critical-soft text-critical' : 'bg-accent-soft text-accent-ink'">
                    <template x-if="tone === 'danger'">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5" />
                    </template>
                    <template x-if="tone !== 'danger'">
                        <x-heroicon-o-question-mark-circle class="h-5 w-5" />
                    </template>
                </span>

                <div class="min-w-0 pt-0.5">
                    <h2 class="font-[family-name:var(--font-display)] text-base font-semibold text-ink" x-text="title"></h2>
                    <p class="mt-1 text-sm leading-relaxed text-ink-soft" x-text="message"></p>
                </div>
            </div>

            {{-- Confirm first on desktop reading order, but stacked on a phone
                 with cancel underneath, so the destructive one is never the
                 button sitting under a thumb reaching for the bottom edge. --}}
            <div class="mt-5 flex flex-col-reverse gap-2 border-t border-hairline bg-raised px-5 py-3.5 sm:flex-row sm:justify-end">
                <button type="button" x-on:click="cancel()"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-lg border border-hairline-strong bg-surface px-4 py-2 text-sm font-medium text-ink transition hover:bg-sunken sm:min-h-[38px]">
                    Cancel
                </button>

                <button type="button" x-ref="confirm" x-on:click="proceed()"
                    :class="tone === 'danger'
                        ? 'bg-critical text-white hover:opacity-90'
                        : 'bg-accent text-on-accent hover:opacity-90'"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition elevate focus:outline-none focus:ring-2 focus:ring-accent/40 sm:min-h-[38px]">
                    <span x-text="action"></span>
                </button>
            </div>
        </div>
    </div>
</div>
