/**
 * "Back" links that go where the person actually came from.
 *
 * Every page header names a sensible parent ("Payments", "Members") as its
 * fallback, but someone who opened Collect fee from a member's page expects
 * Back to return to that member, not to the payment list. The browser
 * history knows the way; what it cannot tell us is whether the previous
 * entry is one of ours. So each page visit is noted here, and Back steps
 * through the browser history only when that note says the previous page was
 * inside the app — otherwise it follows the fallback link as before.
 *
 * `wire:navigate` replaces the document without a reload, so the note is
 * kept in sessionStorage and refreshed on every `livewire:navigated`, which
 * also fires on the first load of a page.
 */
const KEY = 'app.history';
const LIMIT = 30;

const read = () => {
    try {
        const stored = JSON.parse(sessionStorage.getItem(KEY) ?? '[]');

        return Array.isArray(stored) ? stored : [];
    } catch {
        return [];
    }
};

const write = (entries) => {
    try {
        sessionStorage.setItem(KEY, JSON.stringify(entries.slice(-LIMIT)));
    } catch {
        // Storage unavailable: Back simply follows the fallback link.
    }
};

const record = () => {
    const entries = read();
    const current = window.location.href;

    // A refresh, or a Livewire redirect that lands where we already are,
    // must not count as a step.
    if (entries[entries.length - 1] === current) {
        return;
    }

    // Going back removes the page we left, so the note mirrors the history
    // rather than growing on every return.
    if (entries[entries.length - 2] === current) {
        entries.pop();
        write(entries);

        return;
    }

    entries.push(current);
    write(entries);
};

/**
 * Whether the previous history entry is a page of this app that we noted.
 */
const canStepBack = () => {
    const entries = read();

    return entries.length >= 2 && entries[entries.length - 1] === window.location.href;
};

document.addEventListener('livewire:navigated', record);

// Delegated, and in the capture phase: `wire:navigate` listens on the link
// itself, so this has to decide first and stop the event reaching it when
// the browser history is the way to go.
document.addEventListener('click', (event) => {
    const link = event.target.closest('[data-back]');

    if (!link || event.metaKey || event.ctrlKey || event.shiftKey || event.button !== 0) {
        return;
    }

    if (canStepBack()) {
        event.preventDefault();
        event.stopPropagation();
        window.history.back();
    }

    // Otherwise the anchor's own href (the named parent page) is followed.
}, true);
