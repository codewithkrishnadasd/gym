/**
 * The filter bar above every list (components/ui/filters.blade.php).
 *
 * Filters live in a popover (a bottom sheet on phones). Changes made there
 * are staged, not sent: the bar stops the select's change event before
 * Livewire sees it, and on "Apply" pushes every value at once as one
 * request. Cancelling puts the controls back to what is applied. Applied
 * filters show as chips next to the button, each removable on its own.
 *
 * Date fields inside the bar defer themselves (see date-field.js), so they
 * take part in the same apply/cancel cycle through $wire.
 */
function propertyOf(el) {
    for (const attr of el.getAttributeNames()) {
        if (attr.startsWith('wire:model')) {
            return el.getAttribute(attr);
        }
    }

    return el.dataset.property ?? null;
}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('filtersBar', () => ({
        open: false,
        snapshot: {},
        chips: [],

        init() {
            this.refresh();
            // Re-read after Livewire re-renders the list (new options, values).
            window.Livewire.hook('morphed', () => this.refresh());
        },

        controls() {
            return Array.from(this.$refs.panel.querySelectorAll('select[wire\\:model], select[wire\\:model\\.live], [data-date-property]'));
        },

        /** Applied (server-side) value of a control's property. */
        applied(el) {
            const property = el.dataset.dateProperty ?? propertyOf(el);

            return property ? (this.$wire.get(property) ?? '') : '';
        },

        labelOf(el) {
            return el.dataset.filterLabel ?? el.getAttribute('aria-label') ?? 'Filter';
        },

        textOf(el, value) {
            if (el.tagName === 'SELECT') {
                const option = Array.from(el.options).find((o) => o.value === String(value));

                return option ? option.text.trim() : String(value);
            }

            // ISO date → "16 Sep 2026", as the field itself shows it.
            const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(String(value));

            return match ? `${Number(match[3])} ${['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][Number(match[2]) - 1]} ${match[1]}` : String(value);
        },

        refresh() {
            if (!this.$refs.panel) {
                return;
            }

            this.chips = this.controls()
                .map((el) => ({ el, value: this.applied(el) }))
                .filter(({ el, value }) => value !== '' && value !== (el.dataset.filterDefault ?? ''))
                .map(({ el, value }) => ({
                    property: el.dataset.dateProperty ?? propertyOf(el),
                    label: this.labelOf(el),
                    text: this.textOf(el, value),
                }));
        },

        get active() {
            return this.chips.length;
        },

        pos: { top: 0, left: 0 },

        /**
         * Where the popover goes on wider screens: under the button, kept
         * inside the viewport. Positioned `fixed` so no card can clip it. On
         * phones the panel is a bottom sheet and needs no coordinates.
         */
        place() {
            if (!this.open || !this.$refs.trigger) {
                return;
            }

            const rect = this.$refs.trigger.getBoundingClientRect();
            const width = 352;
            const left = Math.max(8, Math.min(rect.left, window.innerWidth - width - 8));

            this.pos = { top: Math.round(rect.bottom + 8), left: Math.round(left) };
        },

        panelStyle() {
            if (window.matchMedia('(max-width: 639px)').matches) {
                return {};
            }

            return { top: `${this.pos.top}px`, left: `${this.pos.left}px` };
        },

        show() {
            // Remember what is applied so Cancel can put it back.
            this.snapshot = {};

            for (const el of this.controls()) {
                const property = el.dataset.dateProperty ?? propertyOf(el);

                if (property) {
                    this.snapshot[property] = this.applied(el);
                }
            }

            this.open = true;
            this.place();
            window.dispatchEvent(new CustomEvent('combobox:sync'));
        },

        /** Stops a select's change reaching Livewire; the value waits for Apply. */
        stage(event) {
            if (event.target.tagName === 'SELECT' && propertyOf(event.target)) {
                event.stopPropagation();
            }
        },

        apply() {
            for (const el of this.controls()) {
                if (el.tagName === 'SELECT') {
                    this.$wire.set(propertyOf(el), el.value, false);
                }
            }

            this.open = false;
            this.$wire.$commit().then(() => this.refresh());
        },

        cancel() {
            for (const el of this.controls()) {
                const property = el.dataset.dateProperty ?? propertyOf(el);

                if (!property) {
                    continue;
                }

                if (el.tagName === 'SELECT') {
                    // The combobox over it re-reads the value on this signal.
                    el.value = this.snapshot[property] ?? '';
                } else {
                    // Date fields follow $wire; a deferred set back to the
                    // applied value is a no-op for the server.
                    this.$wire.set(property, this.snapshot[property] ?? '', false);
                }
            }

            this.open = false;
            window.dispatchEvent(new CustomEvent('combobox:sync'));
        },

        clearOne(property) {
            this.$wire.set(property, '', true).then(() => this.refresh());
        },

        clearAll() {
            for (const el of this.controls()) {
                const property = el.dataset.dateProperty ?? propertyOf(el);

                if (property) {
                    this.$wire.set(property, el.dataset.filterDefault ?? '', false);
                }
            }

            this.open = false;
            this.$wire.$commit().then(() => this.refresh());
        },
    }));
});
