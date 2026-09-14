{{--
    Catches `beforeinstallprompt` before anything else can miss it.

    Chrome fires this event as soon as the install criteria are met, which is
    usually well before Alpine has booted — Alpine arrives with Livewire at the
    end of the body. A listener registered inside an Alpine component therefore
    never sees it, the event is gone for the rest of the page's life, and the
    install button silently never appears.

    So the event is caught here, in the head, and parked on `window`. The button
    reads that on init and also listens for the re-dispatched event, which
    covers both orders: fired before Alpine, or after.
--}}
<script>
    (() => {
        window.installPrompt = null;

        window.addEventListener('beforeinstallprompt', (event) => {
            // Stops Chrome's own mini-infobar and keeps the event usable later.
            event.preventDefault();

            window.installPrompt = event;
            window.dispatchEvent(new CustomEvent('install-available'));
        });

        window.addEventListener('appinstalled', () => {
            window.installPrompt = null;
            window.dispatchEvent(new CustomEvent('install-completed'));
        });
    })();
</script>
