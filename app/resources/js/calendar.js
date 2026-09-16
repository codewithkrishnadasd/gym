/**
 * The calendar behind every date control (components/ui/calendar-panel,
 * date-range and date-control). One component, two modes:
 *
 *   range  — first click starts, second ends (either order), applied together;
 *   single — one click picks the date.
 *
 * The month title opens a grid of months, the year a grid of years, so a
 * date of birth years back is three taps away rather than a hundred
 * clicks. Nothing is native: the browser's own picker differs on every
 * platform and cannot show a range.
 */
const DAY = 24 * 60 * 60 * 1000;

const pad = (n) => String(n).padStart(2, '0');
export const iso = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
export const parse = (value) => {
    const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value ?? '');

    return match ? new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3])) : null;
};

const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

document.addEventListener('alpine:init', () => {
    window.Alpine.data('calendar', ({ mode = 'range', from = null, to = null, today = null, futureAllowed = false, anchor = null, apply }) => ({
        mode,
        open: false,
        start: from || null,
        end: to || null,
        hover: null,
        // 'days' | 'months' | 'years' — what the grid shows right now.
        view: 'days',
        // The month shown (left, when two), as the first of that month.
        cursor: null,
        // First year of the 12-year grid.
        yearBase: 0,
        today,
        futureAllowed,
        weekdays: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
        months: SHORT,

        init() {
            this.focusCursor();
        },

        focusCursor() {
            const anchor = parse(this.end ?? this.start) ?? parse(this.today) ?? new Date();
            this.cursor = new Date(anchor.getFullYear(), anchor.getMonth(), 1);

            if (this.mode === 'range' && this.wide() && this.start && this.end && parse(this.start).getMonth() !== parse(this.end).getMonth()) {
                this.cursor = new Date(parse(this.start).getFullYear(), parse(this.start).getMonth(), 1);
            }
        },

        wide() {
            return window.matchMedia('(min-width: 640px)').matches;
        },

        // Two months side by side only for a range on a wide screen.
        twoMonths() {
            return this.mode === 'range' && this.wide();
        },

        /** Opens with a value handed in — the single-date control's current text. */
        openWith(value) {
            this.start = value || null;
            this.end = null;
            this.view = 'days';
            this.focusCursor();
            this.open = true;
            this.place();
        },

        toggle() {
            this.open = !this.open;

            if (this.open) {
                this.hover = null;
                this.view = 'days';
                this.place();
            }
        },

        close() {
            this.open = false;
        },

        // ---- positioning: under the trigger, kept inside the viewport.
        pos: { top: 0, left: 0, width: 608 },

        panelWidth() {
            return this.twoMonths() ? 608 : 336;
        },

        place() {
            // The element the panel hangs under: a ref inside this scope, or
            // one handed in from outside it (the date field's button).
            const trigger = this.$refs.trigger ?? anchor?.();

            if (!this.wide() || !trigger) {
                return;
            }

            this.$nextTick(() => {
                const rect = trigger.getBoundingClientRect();
                const gutter = 12;
                const width = Math.min(this.panelWidth(), window.innerWidth - gutter * 2);
                const left = Math.min(Math.max(rect.left, gutter), window.innerWidth - width - gutter);
                // Below when there is room, otherwise above.
                const below = rect.bottom + 8;
                const top = below + 420 > window.innerHeight && rect.top > 440 ? Math.max(gutter, rect.top - 8 - 420) : below;

                this.pos = { top, left, width };
            });
        },

        panelStyle() {
            return this.wide()
                ? `top:${this.pos.top}px;left:${this.pos.left}px;width:${this.pos.width}px`
                : '';
        },

        // ---- month / year navigation
        shift(months) {
            this.cursor = new Date(this.cursor.getFullYear(), this.cursor.getMonth() + months, 1);
        },

        shiftYears(years) {
            if (this.view === 'years') {
                this.yearBase += years * 12;

                return;
            }

            this.cursor = new Date(this.cursor.getFullYear() + years, this.cursor.getMonth(), 1);
        },

        showMonths() {
            this.view = this.view === 'months' ? 'days' : 'months';
        },

        showYears() {
            if (this.view === 'years') {
                this.view = 'days';

                return;
            }

            this.yearBase = this.cursor.getFullYear() - 5;
            this.view = 'years';
        },

        pickMonth(index) {
            this.cursor = new Date(this.cursor.getFullYear(), index, 1);
            this.view = 'days';
        },

        pickYear(year) {
            this.cursor = new Date(year, this.cursor.getMonth(), 1);
            this.view = 'months';
        },

        years() {
            return Array.from({ length: 12 }, (_, i) => this.yearBase + i);
        },

        yearRangeLabel() {
            return `${this.yearBase} – ${this.yearBase + 11}`;
        },

        monthName(offset = 0) {
            const d = new Date(this.cursor.getFullYear(), this.cursor.getMonth() + offset, 1);

            return MONTHS[d.getMonth()];
        },

        yearOf(offset = 0) {
            return new Date(this.cursor.getFullYear(), this.cursor.getMonth() + offset, 1).getFullYear();
        },

        isCurrentMonth(index) {
            return this.cursor.getMonth() === index;
        },

        // ---- the day grid
        cells(offset) {
            const first = new Date(this.cursor.getFullYear(), this.cursor.getMonth() + offset, 1);
            const lead = (first.getDay() + 6) % 7; // Monday first
            const count = new Date(first.getFullYear(), first.getMonth() + 1, 0).getDate();
            const cells = [];

            for (let i = 0; i < lead; i++) {
                cells.push(null);
            }

            for (let day = 1; day <= count; day++) {
                cells.push(iso(new Date(first.getFullYear(), first.getMonth(), day)));
            }

            while (cells.length % 7 !== 0) {
                cells.push(null);
            }

            return cells;
        },

        bounds() {
            const a = this.start;
            const b = this.mode === 'range' ? (this.end ?? this.hover ?? this.start) : this.start;

            if (!a || !b) {
                return [a, a];
            }

            return a <= b ? [a, b] : [b, a];
        },

        isStart(date) {
            return date === this.bounds()[0];
        },

        isEnd(date) {
            return date === this.bounds()[1];
        },

        inRange(date) {
            const [a, b] = this.bounds();

            return this.mode === 'range' && a && b && date > a && date < b;
        },

        isFuture(date) {
            return !this.futureAllowed && this.today && date > this.today;
        },

        pick(date) {
            if (this.isFuture(date)) {
                return;
            }

            if (this.mode === 'single') {
                this.start = date;
                apply(date);
                this.close();

                return;
            }

            if (!this.start || this.end) {
                this.start = date;
                this.end = null;
                this.hover = null;

                return;
            }

            this.end = date;

            if (this.end < this.start) {
                [this.start, this.end] = [this.end, this.start];
            }

            apply(this.start, this.end);
            this.close();
        },

        quick(kind) {
            const t = parse(this.today) ?? new Date();
            const spans = { last7: 6, last30: 29, last90: 89 };

            if (!(kind in spans)) {
                return;
            }

            this.start = iso(new Date(t.getTime() - spans[kind] * DAY));
            this.end = iso(t);
            apply(this.start, this.end);
            this.close();
        },

        goToday() {
            const t = parse(this.today) ?? new Date();
            this.cursor = new Date(t.getFullYear(), t.getMonth(), 1);
            this.view = 'days';
        },

        label() {
            const f = (value) => {
                const d = parse(value);

                return d ? `${d.getDate()} ${SHORT[d.getMonth()]} ${d.getFullYear()}` : '…';
            };

            if (this.mode === 'single') {
                return this.start ? f(this.start) : 'Pick a date';
            }

            if (this.start && this.end) {
                return `${f(this.start)} – ${f(this.end)}`;
            }

            return this.start ? `${f(this.start)} – pick an end date` : 'Pick a date range';
        },

        days() {
            const a = parse(this.start);
            const b = parse(this.end);

            return a && b ? Math.round((b - a) / DAY) + 1 : 0;
        },
    }));
});
