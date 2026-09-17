/**
 * The feel of moving between pages.
 *
 * Every link is wire:navigate, so a page change swaps the body in place
 * rather than reloading. Pages themselves are deferred (LazyPage): the shell
 * and a skeleton arrive first and the content follows. This adds the motion
 * around that: the outgoing page dims the instant a link is tapped, and the
 * incoming one rises into place; then, when the deferred content lands, it
 * fades in over the skeleton.
 */
const html = document.documentElement;

document.addEventListener('livewire:navigate', () => {
    html.setAttribute('data-navigating', '');
});

const arrived = () => {
    html.removeAttribute('data-navigating');

    const main = document.querySelector('main');

    if (!main) {
        return;
    }

    main.classList.remove('page-enter');
    // Restart the animation even when the same element is reused.
    void main.offsetWidth;
    main.classList.add('page-enter');
    main.addEventListener('animationend', () => main.classList.remove('page-enter'), { once: true });
};

document.addEventListener('livewire:navigated', arrived);
document.addEventListener('livewire:navigate-failed', () => html.removeAttribute('data-navigating'));

// Deferred content replacing a skeleton: fade the real page in, once per
// page component — later updates to it (a filter, a save) do not re-run it.
const arrivedRoots = new WeakSet();

document.addEventListener('livewire:init', () => {
    window.Livewire.hook('morphed', ({ el }) => {
        const main = document.querySelector('main');

        if (!main || el.parentElement !== main || arrivedRoots.has(el) || el.querySelector('.page-skeleton')) {
            return;
        }

        arrivedRoots.add(el);
        el.classList.add('content-enter');
        el.addEventListener('animationend', () => el.classList.remove('content-enter'), { once: true });
    });
});
