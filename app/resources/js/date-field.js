/**
 * The dd/mm/yyyy date field (components/ui/date-input.blade.php).
 *
 * Browsers render <input type="date"> in whatever order the OS locale says,
 * which for an Indian gym on a US-locale laptop means mm/dd/yyyy. This field
 * is a plain text box that always reads and writes dd/mm/yyyy, and talks to
 * the Livewire property in ISO (yyyy-mm-dd) so nothing server-side changes.
 *
 * The calendar button opens the shared calendar (calendar.js) in
 * single-date mode; nothing native is involved.
 */
const ISO = /^(\d{4})-(\d{2})-(\d{2})$/;

export function toDisplay(iso) {
    const match = ISO.exec(iso ?? '');

    return match ? `${match[3]}/${match[2]}/${match[1]}` : '';
}

/** Returns yyyy-mm-dd for a complete, real dd/mm/yyyy date; otherwise null. */
export function toIso(text) {
    const match = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(text ?? '');

    if (!match) {
        return null;
    }

    const [day, month, year] = [Number(match[1]), Number(match[2]), Number(match[3])];
    const date = new Date(Date.UTC(year, month - 1, day));

    const real = date.getUTCFullYear() === year && date.getUTCMonth() === month - 1 && date.getUTCDate() === day;

    return real && year >= 1900 ? `${match[3]}-${match[2]}-${match[1]}` : null;
}

/** Keeps only digits and lays them out as dd/mm/yyyy while typing. */
export function mask(text) {
    const digits = (text ?? '').replace(/\D/g, '').slice(0, 8);
    const parts = [digits.slice(0, 2), digits.slice(2, 4), digits.slice(4, 8)].filter((part, index) => index === 0 || part.length > 0);

    // A completed pair gets its slash straight away, so the next keystroke
    // lands in the right place without the user typing the separator.
    let out = parts.join('/');

    if ((digits.length === 2 || digits.length === 4) && !text.endsWith('/')) {
        out += '/';
    }

    return out;
}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('dateField', ({ property, live }) => ({
        text: '',
        invalid: false,
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

                if (toIso(this.text) !== current) {
                    this.text = toDisplay(current);
                    this.invalid = false;
                }
            });
        },

        onInput(event) {
            const masked = mask(event.target.value);

            this.text = masked;
            this.invalid = false;

            // Commit as soon as the date is complete and real; a live binding
            // then runs its request without waiting for blur.
            if (toIso(masked) !== null) {
                this.commit();
            }
        },

        onBlur() {
            if (this.text === '') {
                this.commit();

                return;
            }

            if (toIso(this.text) === null) {
                // Half a date is not a date: show it was not accepted, then
                // fall back to whatever the server still holds.
                this.invalid = true;

                setTimeout(() => {
                    this.text = toDisplay(this.$wire.get(property) ?? '');
                    this.invalid = false;
                }, 900);
            }
        },

        commit() {
            const iso = this.text === '' ? '' : toIso(this.text);

            if (iso === null || iso === (this.$wire.get(property) ?? '')) {
                return;
            }

            this.$wire.set(property, iso, this.live);
        },

        currentIso() {
            return toIso(this.text) ?? '';
        },

        onPick(isoDate) {
            this.text = toDisplay(isoDate);
            this.invalid = false;
            this.commit();
        },
    }));
});
