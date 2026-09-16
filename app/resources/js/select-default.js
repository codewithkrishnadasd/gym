/**
 * Required selects (components/ui/select.blade.php) with a single real
 * option pick it themselves. A placeholder ("Select…", empty value) does not
 * count; a select that already has a value is left alone. The change is
 * dispatched so `wire:model` (deferred or live) records the value.
 *
 * Runs on first load, after every Livewire navigation, and whenever Livewire
 * morphs new selects into the page.
 */
const apply = (root = document) => {
    root.querySelectorAll('select[data-select-only-option]').forEach((select) => {
        if (select.value !== '' || select.disabled) {
            return;
        }

        const real = Array.from(select.options).filter((option) => option.value !== '' && !option.disabled);

        if (real.length !== 1) {
            return;
        }

        select.value = real[0].value;
        select.dispatchEvent(new Event('input', { bubbles: true }));
        select.dispatchEvent(new Event('change', { bubbles: true }));
    });
};

document.addEventListener('livewire:navigated', () => apply());
document.addEventListener('DOMContentLoaded', () => apply());

document.addEventListener('livewire:init', () => {
    window.Livewire.hook('morphed', ({ el }) => apply(el));
});
