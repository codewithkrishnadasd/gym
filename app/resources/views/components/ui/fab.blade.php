@props(['href', 'label', 'symbol' => '+'])

{{--
    The one action a page is really for, as a round button pinned to the
    bottom-right — above the tab bar on a phone, in the corner on a desk.
    The label appears on wider screens; on a phone the symbol stands alone.
--}}
<a href="{{ $href }}" wire:navigate aria-label="{{ $label }}" title="{{ $label }}"
    {{ $attributes->class('fixed bottom-[calc(max(0.375rem,env(safe-area-inset-bottom))+4.5rem)] right-4 z-30 inline-flex h-14 min-w-14 items-center justify-center gap-2 rounded-full bg-accent px-4 font-[family-name:var(--font-display)] text-xl font-semibold text-on-accent shadow-[0_10px_30px_-8px_rgb(var(--c-shadow)/0.45)] transition hover:brightness-110 active:scale-95 lg:bottom-8 lg:right-8') }}>
    <span class="numeric leading-none">{{ $symbol }}</span>
    <span class="hidden text-sm font-medium sm:inline">{{ $label }}</span>
</a>
