/**
 * The custom date range picker (components/ui/date-range.blade.php).
 *
 * A calendar of one month (two side by side on wider screens). The first
 * click marks the start, the second the end — in either order — and the
 * range is sent to the component as one request through `setRange`, so the
 * page never re-queries for a half-chosen range. Nothing is native: the
 * browser's date input gives a different control on every platform and no
 * way to show two ends of one range together.
 */
const DAY = 24 * 60 * 60 * 1000;

const pad = (n) => String(n).padStart(2, '0');
const iso = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
const parse = (value) => {
    const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value ?? '');

    return match ? new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3])) : null;
};

const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

document.addEventListener('alpine:init', () => {
    window.Alpine.data('dateRange', ({ from, to, today, apply }) => ({
        open: false,
        start: from || null,
        end: to || null,
        hover: null,
        // The month shown on the left, as the first of that month.
        cursor: null,
        today,
        weekdays: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],

        init() {
            const anchor = parse(this.end ?? this.start) ?? parse(this.today) ?? new Date();
            this.cursor = new Date(anchor.getFullYear(), anchor.getMonth(), 1);

            // Two months on a wide screen: show the range's own month on the
            // right so the newest dates sit nearest the trigger.
            if (this.wide() && this.start && this.end && parse(this.start).getMonth() !== parse(this.end).getMonth()) {
                this.cursor = new Date(parse(this.start).getFullYear(), parse(this.start).getMonth(), 1);
            }
        },

        wide() {
            return window.matchMedia('(min-width: 640px)').matches;
        },

        toggle() {
            this.open = !this.open;

            if (this.open) {
                this.hover = null;
            }
        },

        close() {
            this.open = false;
        },

        shift(months) {
            this.cursor = new Date(this.cursor.getFullYear(), this.cursor.getMonth() + months, 1);
        },

        /** The month `offset` months after the cursor, as a label. */
        title(offset) {
            const d = new Date(this.cursor.getFullYear(), this.cursor.getMonth() + offset, 1);

            return `${MONTHS[d.getMonth()]} ${d.getFullYear()}`;
        },

        /**
         * The 6×7 grid for a month: leading and trailing cells from the
         * neighbouring months are blank so columns stay under their weekday.
         */
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

        /** The range currently drawn: the chosen one, or start→hover while choosing. */
        bounds() {
            const a = this.start;
            const b = this.end ?? this.hover ?? this.start;

            if (!a || !b) {
                return [a, a];
            }

            return a <= b ? [a, b] : [b, a];
        },

        isStart(date) {
            const [a] = this.bounds();

            return date === a;
        },

        isEnd(date) {
            const [, b] = this.bounds();

            return date === b;
        },

        inRange(date) {
            const [a, b] = this.bounds();

            return a && b && date > a && date < b;
        },

        isFuture(date) {
            return this.today && date > this.today;
        },

        pick(date) {
            if (this.isFuture(date)) {
                return;
            }

            // First click starts a new range; second click completes it.
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

        /** A whole month or the last N days, straight from the picker footer. */
        quick(kind) {
            const t = parse(this.today) ?? new Date();
            let a;
            let b = t;

            if (kind === 'last7') {
                a = new Date(t.getTime() - 6 * DAY);
            } else if (kind === 'last30') {
                a = new Date(t.getTime() - 29 * DAY);
            } else if (kind === 'last90') {
                a = new Date(t.getTime() - 89 * DAY);
            } else {
                return;
            }

            this.start = iso(a);
            this.end = iso(b);
            apply(this.start, this.end);
            this.close();
        },

        label() {
            const f = (value) => {
                const d = parse(value);

                return d ? `${d.getDate()} ${SHORT[d.getMonth()]} ${d.getFullYear()}` : '…';
            };

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
