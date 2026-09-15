{{--
    Resolves the viewer's theme before first paint, and again after every
    Livewire navigation.

    The re-apply is not belt-and-braces: `wire:navigate` morphs the document
    against the server's HTML, which has no `data-theme` attribute, so the value
    set here is dropped and the page silently falls back to the OS preference
    mid-session. That is the theme "changing by itself" on some redirects.
--}}
<script>
    (() => {
        const apply = () => {
            const stored = localStorage.getItem('theme');
            const system = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.dataset.theme = stored ?? system;

            // Navigation style is a per-device preference like the theme:
            // "sidebar" (default) or "launcher" (a Menu button that opens a
            // full-screen grid of every destination).
            document.documentElement.dataset.nav = localStorage.getItem('nav') === 'launcher' ? 'launcher' : 'sidebar';
        };

        apply();

        document.addEventListener('livewire:navigating', apply);
        document.addEventListener('livewire:navigated', apply);
    })();
</script>
