/**
 * The app-wide loading bar (components/ui/page-loader.blade.php).
 *
 * Every kind of wait routes through one counter: Livewire requests,
 * wire:navigate visits, plain form submits such as sign-in, and full page
 * navigations. The bar rushes to a third, trickles towards ninety per cent
 * while work is outstanding, and only runs to the end once the last pending
 * job finishes — so overlapping requests read as a single motion.
 *
 * It appears only after a short grace period: most Livewire round trips are
 * done within it, and a bar that flashes for 60ms is noise, not feedback.
 */
const GRACE_MS = 120;
const TRICKLE_MS = 350;
const SAFETY_MS = 20000;

let pending = 0;
let progress = 0;
let graceTimer = null;
let trickleTimer = null;
let safetyTimer = null;

function element() {
    return document.querySelector('[data-page-loader]');
}

function paint(width) {
    const bar = element()?.firstElementChild;

    if (bar) {
        bar.style.width = `${width}%`;
    }
}

function show() {
    const loader = element();

    if (!loader) {
        return;
    }

    loader.setAttribute('data-active', '');
    loader.setAttribute('aria-hidden', 'false');

    progress = 30;
    paint(progress);

    clearInterval(trickleTimer);
    trickleTimer = setInterval(() => {
        // Ease towards 90 so the bar never sits still, and never lies about
        // being finished.
        progress += (90 - progress) * 0.12;
        paint(progress);
    }, TRICKLE_MS);
}

function hide() {
    const loader = element();

    clearInterval(trickleTimer);
    clearTimeout(graceTimer);
    clearTimeout(safetyTimer);
    graceTimer = null;

    if (!loader || !loader.hasAttribute('data-active')) {
        return;
    }

    paint(100);

    setTimeout(() => {
        loader.removeAttribute('data-active');
        loader.setAttribute('aria-hidden', 'true');

        // Reset the width only once the fade has finished, or it would visibly
        // snap back to zero while still on screen.
        setTimeout(() => {
            if (!loader.hasAttribute('data-active')) {
                paint(0);
            }
        }, 300);
    }, 150);
}

export function start() {
    pending += 1;

    if (pending === 1 && graceTimer === null) {
        graceTimer = setTimeout(() => {
            graceTimer = null;

            if (pending > 0) {
                show();
            }
        }, GRACE_MS);

        // Should a "finished" signal ever be lost, the bar must not live on
        // forever pretending something is still happening.
        clearTimeout(safetyTimer);
        safetyTimer = setTimeout(() => {
            pending = 0;
            hide();
        }, SAFETY_MS);
    }
}

export function finish() {
    pending = Math.max(0, pending - 1);

    if (pending === 0) {
        hide();
    }
}

// Livewire: every request, whether from wire:click, wire:model, or polling.
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('request', ({ succeed, fail }) => {
        start();
        succeed(() => finish());
        fail(() => finish());
    });
});

// wire:navigate visits. A navigation that is cancelled or redirected still
// ends in `livewire:navigated`, so the counter balances.
document.addEventListener('livewire:navigating', () => start());
document.addEventListener('livewire:navigated', () => {
    pending = 0;
    hide();
});

// Plain (non-Livewire) form submits, e.g. the sign-in form. Livewire's
// wire:submit calls preventDefault on the form itself, so by the time the
// event reaches the document a handled form is already marked.
document.addEventListener('submit', (event) => {
    if (event.defaultPrevented || event.target.hasAttribute('wire:submit')) {
        return;
    }

    start();
});

// Full page navigations from ordinary links. Downloads also fire beforeunload
// without the page ever going away, so links marked `data-download` (CSV, PDF,
// receipts) are excluded — the browser's own download UI is their feedback.
let lastDownloadClick = 0;

document.addEventListener(
    'click',
    (event) => {
        const anchor = event.target.closest?.('a[href]');

        if (anchor && (anchor.hasAttribute('data-download') || anchor.hasAttribute('download') || anchor.target === '_blank')) {
            lastDownloadClick = Date.now();
        }
    },
    true,
);

window.addEventListener('beforeunload', () => {
    if (Date.now() - lastDownloadClick < 1000) {
        return;
    }

    start();
});

// A page restored from the back/forward cache comes back with whatever the
// bar was doing when it left.
window.addEventListener('pageshow', () => {
    pending = 0;
    hide();
});
