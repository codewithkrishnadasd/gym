{{-- A shareable link with copy and preview. Uses the window clipboard helper
     because navigator.clipboard is absent outside a secure context. State
     lives on a plain div: @js() inside an x-component's attribute is not
     compiled by Livewire's Blade pass. --}}
@props(['url', 'label' => 'Link', 'hint' => null])

<div x-data="{ copied: false, url: @js($url), async copy() { this.copied = await window.copyToClipboard(this.url); setTimeout(() => this.copied = false, 1800) } }">
    <p class="mb-1.5 text-[13px] font-medium text-ink-soft">{{ $label }}</p>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <input type="text" readonly :value="url" x-on:focus="$el.select()" aria-label="{{ $label }}"
            class="numeric min-h-[40px] min-w-0 flex-1 rounded-lg border border-hairline-strong bg-sunken px-3 py-2 font-mono text-xs text-ink-soft focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
        <div class="flex shrink-0 items-center gap-2">
            <x-ui.button size="sm" icon="clipboard-document" x-on:click="copy">
                <span x-show="! copied">Copy link</span>
                <span x-show="copied" x-cloak class="text-positive">Copied</span>
            </x-ui.button>
            <x-ui.button size="sm" variant="ghost" icon="arrow-top-right-on-square" :href="$url" target="_blank" rel="noopener">Preview</x-ui.button>
        </div>
    </div>
    @if ($hint)
        <p class="mt-1.5 text-xs text-ink-muted">{{ $hint }}</p>
    @endif
</div>
