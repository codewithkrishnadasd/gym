{{--
    Writes the choice to localStorage and flips `data-theme` on <html>, which
    is the same attribute <x-theme-script> reads back on load and after every
    Livewire navigation. Initial state comes from localStorage rather than the
    attribute, so the button stays correct even if it is re-initialised before
    the attribute has been re-applied.
--}}
@props(['label' => false])

<button type="button"
    x-data="{
        theme: localStorage.getItem('theme')
            ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        toggle() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = this.theme;
            localStorage.setItem('theme', this.theme);
        },
    }"
    @click="toggle()"
    :aria-label="theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
    {{ $attributes->class(['inline-flex items-center gap-2.5 rounded-lg text-sm text-ink-soft transition hover:bg-sunken hover:text-ink', 'h-9 w-9 justify-center' => ! $label, 'px-3 py-2' => $label]) }}>
    <x-heroicon-o-sun x-show="theme === 'dark'" x-cloak class="h-[18px] w-[18px]" />
    <x-heroicon-o-moon x-show="theme !== 'dark'" x-cloak class="h-[18px] w-[18px]" />
    @if ($label)
        <span x-text="theme === 'dark' ? 'Use light theme' : 'Use dark theme'"></span>
    @endif
</button>
