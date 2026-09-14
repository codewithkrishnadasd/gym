{{--
    "Install as desktop app", shown only when the browser will actually do it.

    Gating on the `beforeinstallprompt` event rather than sniffing the user
    agent is what makes this correct: only Chromium browsers fire it, and only
    once the site genuinely meets the install criteria (manifest, icons, a
    registered service worker, a secure origin). Safari and Firefox never fire
    it, so they never see a button that would do nothing — and neither does
    someone in Chrome who has already installed it.
--}}
<div
    x-data="{
        // Read from the window on init: the event has usually already fired and
        // been parked there by the head script before Alpine ever runs.
        prompt: window.installPrompt ?? null,
        installing: false,
        get available() {
            return this.prompt !== null;
        },
        async install() {
            if (! this.prompt) {
                return;
            }

            this.installing = true;

            this.prompt.prompt();
            await this.prompt.userChoice;

            // The event is single-use: whether they accepted or dismissed, the
            // browser will fire a fresh one if it still wants to offer.
            this.prompt = null;
            this.installing = false;
        },
    }"
    x-on:install-available.window="prompt = window.installPrompt"
    x-on:install-completed.window="prompt = null"
    x-show="available"
    x-cloak
    class="px-3 pb-2">

    <button type="button" x-on:click="install()" :disabled="installing"
        class="flex w-full items-center gap-2.5 rounded-lg border border-dashed border-hairline-strong px-3 py-2.5 text-left text-sm font-medium text-ink-soft transition hover:border-accent hover:bg-sunken hover:text-ink disabled:opacity-60">
        <x-heroicon-o-arrow-down-tray class="h-[18px] w-[18px] shrink-0" />
        <span class="min-w-0 flex-1">
            <span class="block truncate leading-tight">Install as app</span>
            <span class="block truncate text-xs font-normal text-ink-muted">Opens in its own window</span>
        </span>
    </button>
</div>
