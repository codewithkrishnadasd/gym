{{--
    Writes the choice to localStorage and flips `data-theme` on <html>, which
    is the same attribute the pre-paint script in the layout reads back.
--}}
<button type="button"
    x-data="{
        theme: document.documentElement.dataset.theme,
        toggle() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = this.theme;
            localStorage.setItem('theme', this.theme);
        },
    }"
    @click="toggle()"
    :aria-label="theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
    class="grid h-9 w-9 place-items-center rounded-lg text-ink-soft transition hover:bg-sunken hover:text-ink">
    <x-heroicon-o-sun x-show="theme === 'dark'" x-cloak class="h-[18px] w-[18px]" />
    <x-heroicon-o-moon x-show="theme !== 'dark'" x-cloak class="h-[18px] w-[18px]" />
</button>
