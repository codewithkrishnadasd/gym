/**
 * The date field (components/ui/date-input.blade.php): the chosen date as
 * text, and the shared calendar (calendar.js) to change it. It talks to the
 * Livewire property in ISO (yyyy-mm-dd), so nothing server-side changes,
 * and shows dates the way the rest of the app does ("16 Sep 2026").
 */
const ISO = /^(\d{4})-(\d{2})-(\d{2})$/;
const SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

export function toDisplay(iso) {
    const match = ISO.exec(iso ?? '');

    return match ? `${Number(match[3])} ${SHORT[Number(match[2]) - 1]} ${match[1]}` : '';
}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('dateField', ({ property, live }) => ({
        iso: '',
        display: '',
        pickerOpen: false,
        live,

        init() {
            // Inside a filter bar the value waits for "Apply" like the other
            // controls, however the field was declared.
            if (this.$el.closest('[data-filters]')) {
                this.live = false;
            }

            this.$el.dataset.dateProperty = property;

            // Follow the server: a property set from PHP (a prefilled start
            // date, "Start today") must show up here without a page load.
            window.Alpine.effect(() => {
                const current = this.$wire.get(property) ?? '';

                if (current !== this.iso) {
                    this.iso = current;
                    this.display = toDisplay(current);
                }
            });
        },

        onPick(isoDate) {
            this.iso = isoDate;
            this.display = toDisplay(isoDate);
            this.commit();
        },

        clear() {
            this.iso = '';
            this.display = '';
            this.commit();
        },

        commit() {
            if (this.iso === (this.$wire.get(property) ?? '')) {
                return;
            }

            this.$wire.set(property, this.iso, this.live);
        },
    }));
});
