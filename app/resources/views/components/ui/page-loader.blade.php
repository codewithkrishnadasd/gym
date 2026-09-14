{{-- The one loading indicator for the whole app: a full-screen veil that
     blurs the page with a ring spinner in the centre, for any Livewire
     request, wire:navigate visit, plain form submit (sign-in included) or
     full page load. Driven by resources/js/loader.js.

     `data-no-progress-bar` tells Livewire to leave its own navigate bar off so
     the two never stack. --}}
<div data-page-loader data-no-progress-bar role="status" aria-live="polite" aria-label="Loading" aria-hidden="true" class="page-loader">
    <div class="page-loader__ring" aria-hidden="true">
        <svg viewBox="0 0 48 48" fill="none">
            <circle class="page-loader__track" cx="24" cy="24" r="20" stroke-width="4" />
            <circle class="page-loader__arc" cx="24" cy="24" r="20" stroke-width="4" stroke-linecap="round" />
        </svg>
    </div>
    <span class="page-loader__label">Loading…</span>
</div>
