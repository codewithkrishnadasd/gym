{{-- The one loading indicator for the whole app: a slim accent bar along the
     top that fills for any Livewire request, wire:navigate visit, plain form
     submit (sign-in included) or full page load. Driven by resources/js/loader.js.

     `data-no-progress-bar` tells Livewire to leave its own navigate bar off so
     the two never stack. --}}
<div data-page-loader data-no-progress-bar role="progressbar" aria-label="Loading" aria-hidden="true" class="page-loader">
    <div class="page-loader__bar"></div>
</div>
