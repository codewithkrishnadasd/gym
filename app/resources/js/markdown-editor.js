import { marked } from 'marked';
import TurndownService from 'turndown';

/**
 * The task description editor (components/ui/markdown-editor.blade.php).
 *
 * Two ways of working on one piece of markdown. "Code" is the raw text.
 * "Preview" renders it and is itself editable: whatever is typed there is
 * converted back to markdown (Turndown), so the stored value is always
 * markdown and the two views never drift. Both write to the same Livewire
 * property through $wire.
 *
 * Rendered HTML is produced from markdown the user just typed, in their own
 * browser — never from another person's text — so it is safe to place in the
 * DOM. On the server the same markdown is rendered with raw HTML stripped.
 */
marked.setOptions({ gfm: true, breaks: true });

const turndown = new TurndownService({
    headingStyle: 'atx',
    bulletListMarker: '-',
    codeBlockStyle: 'fenced',
    emDelimiter: '_',
});

// GitHub-style task list items survive the round trip.
turndown.addRule('taskListItems', {
    filter: (node) => node.type === 'checkbox' && node.parentNode.nodeName === 'LI',
    replacement: (content, node) => (node.checked ? '[x] ' : '[ ] '),
});

export function render(markdown) {
    return marked.parse(markdown ?? '');
}

export function toMarkdown(html) {
    return turndown.turndown(html ?? '').trim();
}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('markdownEditor', ({ property }) => ({
        // Preview is where most people write; Code is for the markdown-minded.
        mode: 'preview',
        help: false,
        text: '',
        html: '',
        // Set while the preview is being typed in, so the effect below does
        // not re-render the element under the caret.
        typingInPreview: false,

        init() {
            window.Alpine.effect(() => {
                const current = this.$wire.get(property) ?? '';

                if (current !== this.text) {
                    this.text = current;

                    if (!this.typingInPreview) {
                        this.html = render(current);
                    }
                }
            });
        },

        show(mode) {
            if (mode === 'preview') {
                this.html = render(this.text);
            }

            this.mode = mode;

            this.$nextTick(() => {
                const target = mode === 'preview' ? this.$refs.preview : this.$refs.code;

                target?.focus();
            });
        },

        onCode(event) {
            this.text = event.target.value;
            this.$wire.set(property, this.text, false);
        },

        onPreviewInput(event) {
            this.typingInPreview = true;
            this.text = toMarkdown(event.target.innerHTML);
            this.$wire.set(property, this.text, false);
        },

        onPreviewBlur() {
            // Now that the caret has left, re-render so the preview shows the
            // normalised result of what was typed.
            this.typingInPreview = false;
            this.html = render(this.text);
        },

        onPreviewPaste(event) {
            // Plain text only: pasted rich HTML brings styles and scripts
            // along that have no place in a task.
            event.preventDefault();
            document.execCommand('insertText', false, event.clipboardData.getData('text/plain'));
        },

        /** Wraps the current selection in the code view, e.g. **bold**. */
        wrap(before, after = before) {
            const el = this.$refs.code;

            if (this.mode !== 'code' || !el) {
                document.execCommand(before === '**' ? 'bold' : before === '_' ? 'italic' : 'insertText', false, before === '`' ? '`' : undefined);

                return;
            }

            const { selectionStart: start, selectionEnd: end, value } = el;
            const selected = value.slice(start, end) || 'text';

            el.value = value.slice(0, start) + before + selected + after + value.slice(end);
            el.setSelectionRange(start + before.length, start + before.length + selected.length);
            el.dispatchEvent(new Event('input', { bubbles: true }));
        },

        /** Prefixes each selected line in the code view, e.g. "- ". */
        prefixLines(prefix) {
            const el = this.$refs.code;

            if (this.mode !== 'code' || !el) {
                document.execCommand(prefix === '1. ' ? 'insertOrderedList' : prefix === '- ' ? 'insertUnorderedList' : 'formatBlock', false, prefix === '# ' ? 'h2' : undefined);

                return;
            }

            const { selectionStart: start, selectionEnd: end, value } = el;
            const lineStart = value.lastIndexOf('\n', start - 1) + 1;
            const block = value.slice(lineStart, end);
            const prefixed = block.split('\n').map((line) => prefix + line).join('\n');

            el.value = value.slice(0, lineStart) + prefixed + value.slice(end);
            el.setSelectionRange(lineStart, lineStart + prefixed.length);
            el.dispatchEvent(new Event('input', { bubbles: true }));
        },
    }));
});
