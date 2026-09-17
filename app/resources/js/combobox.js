/**
 * Every dropdown (components/ui/select.blade.php, filter-select.blade.php)
 * is a combobox: a button showing the choice, and a panel with a search box
 * and the options — the first five at a time, "Show more" for the rest.
 *
 * The native <select> stays in the DOM, hidden, and remains the source of
 * truth: it holds the options (Livewire re-renders them), carries the
 * wire:model / name / wire:change bindings, and receives the chosen value
 * with a change event, so everything that listened to the select before —
 * Livewire, the filter bar's staging, select-default.js — still does.
 *
 * On phones the panel is a bottom sheet; on wider screens a popover under
 * the button, positioned `fixed` so no card can clip it.
 */
const PAGE = 5;

document.addEventListener('alpine:init', () => {
    window.Alpine.data('combobox', () => ({
        open: false,
        query: '',
        limit: PAGE,
        active: -1,
        options: [],
        value: '',
        label: '',
        placeholder: '',
        disabled: false,
        pos: { top: 0, left: 0, width: 0 },

        init() {
            this.sync();

            // Livewire changes the select's value and options without events.
            this.$watch('open', (open) => open && this.sync());
        },

        select() {
            return this.$root.querySelector('select');
        },

        /** Re-reads the options and the value from the native select. */
        sync() {
            const select = this.select();

            if (!select) {
                return;
            }

            this.disabled = select.disabled;
            this.options = Array.from(select.options).map((option, index) => ({
                index,
                value: option.value,
                text: option.text.trim(),
                group: option.parentElement.tagName === 'OPTGROUP' ? option.parentElement.label : null,
                disabled: option.disabled,
                placeholder: option.value === '',
            }));

            this.value = select.value;

            const current = this.options.find((option) => option.value === this.value);
            const empty = this.options.find((option) => option.placeholder);

            this.placeholder = empty ? empty.text : 'Select…';
            this.label = current && !current.placeholder ? current.text : '';
        },

        get searchable() {
            return this.options.filter((option) => !option.placeholder).length > PAGE;
        },

        get matches() {
            const needle = this.query.trim().toLowerCase();

            return this.options.filter((option) => {
                // The "no choice" option is reached by clearing, not by search.
                if (option.placeholder) {
                    return needle === '';
                }

                return needle === '' || option.text.toLowerCase().includes(needle) || (option.group ?? '').toLowerCase().includes(needle);
            });
        },

        get visible() {
            return this.matches.slice(0, this.limit);
        },

        get remaining() {
            return Math.max(0, this.matches.length - this.limit);
        },

        /** Whether this option starts a new group in the visible list. */
        startsGroup(option, index) {
            return option.group !== null && (index === 0 || this.visible[index - 1].group !== option.group);
        },

        more() {
            this.limit += PAGE;
        },

        show() {
            if (this.disabled) {
                return;
            }

            this.query = '';
            this.limit = PAGE;
            this.open = true;
            this.place();

            this.$nextTick(() => {
                this.active = Math.max(0, this.visible.findIndex((option) => option.value === this.value));
                this.$refs.search?.focus({ preventScroll: true });
            });
        },

        close() {
            this.open = false;
            this.active = -1;
        },

        toggle() {
            this.open ? this.close() : this.show();
        },

        pick(option) {
            if (option.disabled) {
                return;
            }

            const select = this.select();

            if (select && select.value !== option.value) {
                select.value = option.value;
                select.dispatchEvent(new Event('input', { bubbles: true }));
                select.dispatchEvent(new Event('change', { bubbles: true }));
            }

            this.sync();
            this.close();
            this.$refs.trigger?.focus({ preventScroll: true });
        },

        clear() {
            const empty = this.options.find((option) => option.placeholder);

            if (empty) {
                this.pick(empty);
            }
        },

        move(step) {
            if (!this.open) {
                this.show();

                return;
            }

            const count = this.visible.length;

            if (count === 0) {
                return;
            }

            let next = this.active + step;

            if (next >= count && this.remaining > 0 && step > 0) {
                this.more();
            }

            next = Math.max(0, Math.min(this.visible.length - 1, next));
            this.active = next;

            this.$nextTick(() => this.$refs.list?.children[next]?.scrollIntoView({ block: 'nearest' }));
        },

        choose() {
            const option = this.visible[this.active];

            if (option) {
                this.pick(option);
            }
        },

        /** Under the button, kept inside the viewport, as wide as the button. */
        place() {
            if (!this.open || !this.$refs.trigger) {
                return;
            }

            const rect = this.$refs.trigger.getBoundingClientRect();
            const width = Math.max(rect.width, 224);
            const left = Math.max(8, Math.min(rect.left, window.innerWidth - width - 8));
            const below = window.innerHeight - rect.bottom - 8;
            const openUp = below < 240 && rect.top > below;

            this.pos = {
                top: openUp ? null : Math.round(rect.bottom + 4),
                bottom: openUp ? Math.round(window.innerHeight - rect.top + 4) : null,
                left: Math.round(left),
                width: Math.round(width),
            };
        },

        panelStyle() {
            if (window.matchMedia('(max-width: 639px)').matches) {
                return {};
            }

            const style = { left: `${this.pos.left}px`, width: `${this.pos.width}px` };

            if (this.pos.top !== null) {
                style.top = `${this.pos.top}px`;
            } else {
                style.bottom = `${this.pos.bottom}px`;
            }

            return style;
        },
    }));
});

// After Livewire re-renders, every combobox re-reads its select: options
// may have changed, or the value been set from the server.
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('morphed', () => window.dispatchEvent(new CustomEvent('combobox:sync')));
});
