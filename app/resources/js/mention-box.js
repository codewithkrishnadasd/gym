/**
 * "@" completion for the task comment box (livewire/tasks/show.blade.php).
 *
 * Typing "@" opens a list of colleagues filtered by what follows it; Enter,
 * Tab or a click inserts "@Full Name " and the server later resolves the
 * names to people (App\Support\Tasks\Mentions). The textarea is bound to
 * Livewire in the normal way; this only edits its value.
 */
document.addEventListener('alpine:init', () => {
    window.Alpine.data('mentionBox', ({ people }) => ({
        open: false,
        query: '',
        index: 0,
        start: -1,

        get matches() {
            const needle = this.query.toLowerCase();

            return people.filter((person) => person.name.toLowerCase().includes(needle)).slice(0, 6);
        },

        onInput(event) {
            const el = event.target;
            const upto = el.value.slice(0, el.selectionStart);
            const at = upto.lastIndexOf('@');

            // An "@" that starts a word and whose text so far contains no line
            // break is a mention being typed.
            if (at >= 0 && (at === 0 || /\s/.test(upto[at - 1])) && !upto.slice(at).includes('\n')) {
                this.start = at;
                this.query = upto.slice(at + 1);
                this.index = 0;
                this.open = this.matches.length > 0;

                return;
            }

            this.close();
        },

        onKeydown(event) {
            if (!this.open) {
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.index = (this.index + 1) % this.matches.length;
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.index = (this.index - 1 + this.matches.length) % this.matches.length;
            } else if (event.key === 'Enter' || event.key === 'Tab') {
                event.preventDefault();
                this.pick(this.matches[this.index]);
            } else if (event.key === 'Escape') {
                this.close();
            }
        },

        pick(person) {
            if (!person) {
                return;
            }

            const el = this.$refs.box;
            const caret = el.selectionStart;
            const before = el.value.slice(0, this.start);
            const after = el.value.slice(caret);
            const inserted = `@${person.name} `;

            el.value = before + inserted + after;
            el.dispatchEvent(new Event('input', { bubbles: true }));

            const position = before.length + inserted.length;

            this.close();
            this.$nextTick(() => {
                el.focus();
                el.setSelectionRange(position, position);
            });
        },

        close() {
            this.open = false;
            this.query = '';
            this.start = -1;
        },
    }));
});
