{{--
    Markdown editor with Code and Preview views, both editable — see
    resources/js/markdown-editor.js. Bound to a Livewire property through
    $wire, taken from the wire:model attribute like x-ui.date-input.
--}}
@props(['label' => null, 'name' => null, 'hint' => null, 'required' => false, 'placeholder' => 'Write in Markdown…', 'rows' => 8])

@php
    $bindings = $attributes->whereStartsWith('wire:model')->getAttributes();
    $property = $bindings === [] ? $name : reset($bindings);
    $id = $name ? 'f-'.str_replace('.', '-', $name) : 'md-'.substr(md5((string) $property), 0, 8);
    $invalid = $name && $errors->has($name);
@endphp

<x-ui.field :label="$label" :for="$id" :hint="$hint" :name="$name" :required="$required" :class="$attributes->get('class')">
    <div x-data="markdownEditor({ property: @js($property) })"
        @class(['overflow-hidden rounded-lg border bg-surface focus-within:border-accent focus-within:ring-2 focus-within:ring-accent/25', 'border-critical' => $invalid, 'border-hairline-strong' => ! $invalid])>

        <div class="flex flex-wrap items-center gap-1 border-b border-hairline bg-sunken px-2 py-1.5">
            <div class="inline-flex rounded-md border border-hairline bg-surface p-0.5 text-xs font-medium" role="tablist">
                <button type="button" role="tab" x-on:click="show('code')" x-bind:aria-selected="mode === 'code'"
                    x-bind:class="mode === 'code' ? 'bg-accent text-on-accent shadow-sm' : 'text-ink-soft hover:text-ink'"
                    class="inline-flex items-center gap-1 rounded px-2.5 py-1 transition">
                    <x-heroicon-o-code-bracket class="h-3.5 w-3.5" /> Code
                </button>
                <button type="button" role="tab" x-on:click="show('preview')" x-bind:aria-selected="mode === 'preview'"
                    x-bind:class="mode === 'preview' ? 'bg-accent text-on-accent shadow-sm' : 'text-ink-soft hover:text-ink'"
                    class="inline-flex items-center gap-1 rounded px-2.5 py-1 transition">
                    <x-heroicon-o-eye class="h-3.5 w-3.5" /> Preview
                </button>
            </div>

            <span class="mx-1 hidden h-4 w-px bg-hairline sm:block"></span>

            {{-- Formatting works in either view: markdown syntax in Code,
                 rich commands in Preview. --}}
            <div class="flex items-center gap-0.5 text-ink-soft">
                <button type="button" x-on:click="wrap('**')" class="rounded p-1.5 font-bold hover:bg-surface hover:text-ink" title="Bold" aria-label="Bold">B</button>
                <button type="button" x-on:click="wrap('_')" class="rounded p-1.5 italic hover:bg-surface hover:text-ink" title="Italic" aria-label="Italic">I</button>
                <button type="button" x-on:click="wrap('`')" class="rounded p-1.5 font-mono text-xs hover:bg-surface hover:text-ink" title="Code" aria-label="Inline code">&lt;/&gt;</button>
                <button type="button" x-on:click="prefixLines('# ')" class="rounded p-1.5 text-xs font-semibold hover:bg-surface hover:text-ink" title="Heading" aria-label="Heading">H</button>
                <button type="button" x-on:click="prefixLines('- ')" class="rounded p-1.5 hover:bg-surface hover:text-ink" title="Bulleted list" aria-label="Bulleted list"><x-heroicon-o-list-bullet class="h-4 w-4" /></button>
                <button type="button" x-on:click="prefixLines('1. ')" class="rounded p-1.5 hover:bg-surface hover:text-ink" title="Numbered list" aria-label="Numbered list"><x-heroicon-o-numbered-list class="h-4 w-4" /></button>
                <button type="button" x-on:click="prefixLines('- [ ] ')" class="rounded p-1.5 hover:bg-surface hover:text-ink" title="Checklist" aria-label="Checklist"><x-heroicon-o-check-circle class="h-4 w-4" /></button>
            </div>

            <span class="ml-auto hidden text-[11px] text-ink-muted sm:block" x-text="mode === 'code' ? 'Markdown' : 'Editing the preview — saved as Markdown'"></span>

            <button type="button" x-on:click="help = ! help" x-bind:aria-expanded="help" aria-controls="{{ $id }}-help"
                x-bind:class="help ? 'bg-accent text-on-accent' : 'text-ink-soft hover:bg-surface hover:text-ink'"
                class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium transition" title="How to format">
                <x-heroicon-o-question-mark-circle class="h-4 w-4" /> Help
            </button>
        </div>

        {{-- Formatting guide. Every example is written the way it is typed in
             Code, with what it becomes alongside, so it doubles as a cheat
             sheet for the Preview toolbar. --}}
        <div x-show="help" x-cloak x-collapse id="{{ $id }}-help" class="border-b border-hairline bg-raised px-3 py-3 text-xs text-ink-soft">
            <p class="mb-2 font-medium text-ink">How to format the description</p>
            <p class="mb-2.5">Type these in <strong>Code</strong>, or switch to <strong>Preview</strong> and use the toolbar — either way the result is the same.</p>
            <dl class="grid gap-x-6 gap-y-1.5 sm:grid-cols-2">
                @foreach ([
                    ['# Title', 'Title (large heading)'],
                    ['## Sub title', 'Sub title (smaller heading)'],
                    ['### Section', 'Section heading'],
                    ['**bold text**', 'Bold text'],
                    ['_italic text_', 'Italic text'],
                    ['- First point', 'Bulleted list — one line per item'],
                    ['1. First step', 'Numbered list — one line per step'],
                    ['- [ ] To do', 'Checkbox, not ticked'],
                    ['- [x] Done', 'Checkbox, ticked'],
                    ['> Note', 'Highlighted quote or note'],
                    ['`SKU-88`', 'Inline code or a reference number'],
                    ['[Manual](https://…)', 'Link with its own label'],
                    ['---', 'Horizontal line between sections'],
                    ['Blank line', 'Starts a new paragraph'],
                ] as [$syntax, $meaning])
                    <div class="flex items-baseline gap-2">
                        <dt class="shrink-0"><code class="rounded bg-sunken px-1.5 py-0.5 font-mono text-[11px] text-ink">{{ $syntax }}</code></dt>
                        <dd class="text-ink-soft">{{ $meaning }}</dd>
                    </div>
                @endforeach
            </dl>
            <p class="mt-2.5 text-ink-muted">Headings and lists need to start at the beginning of a line. Indent a list item by two spaces to nest it under the one above.</p>
        </div>

        <textarea x-ref="code" x-show="mode === 'code'" id="{{ $id }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
            x-bind:value="text" x-on:input="onCode" spellcheck="true"
            class="block w-full resize-y border-0 bg-transparent px-3 py-2.5 font-mono text-[13px] leading-relaxed text-ink placeholder:text-ink-muted focus:outline-none focus:ring-0"></textarea>

        <div x-ref="preview" x-show="mode === 'preview'" x-cloak contenteditable="true" x-html="html"
            x-on:input="onPreviewInput" x-on:blur="onPreviewBlur" x-on:paste="onPreviewPaste"
            role="textbox" aria-multiline="true" aria-label="{{ $label ?? 'Description' }} preview"
            class="prose-task min-h-[calc({{ $rows }}*1.625rem)] px-3 py-2.5 text-sm text-ink focus:outline-none"></div>
    </div>
</x-ui.field>
