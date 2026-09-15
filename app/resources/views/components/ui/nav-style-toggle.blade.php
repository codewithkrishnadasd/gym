{{--
    Switches between the sidebar and the full-screen launcher. Stored in
    localStorage and mirrored on <html data-nav>, which <x-theme-script>
    re-applies after every Livewire navigation.
--}}
@props(['label' => false])

<button type="button"
    x-data="{
        nav: localStorage.getItem('nav') === 'launcher' ? 'launcher' : 'sidebar',
        toggle() {
            this.nav = this.nav === 'launcher' ? 'sidebar' : 'launcher';
            document.documentElement.dataset.nav = this.nav;
            localStorage.setItem('nav', this.nav);
            window.dispatchEvent(new CustomEvent('close-launcher'));
        },
    }"
    @click="toggle()"
    :aria-label="nav === 'launcher' ? 'Use the side menu' : 'Use the full-screen menu'"
    :title="nav === 'launcher' ? 'Switch to side menu' : 'Switch to full-screen menu'"
    {{ $attributes->class(['inline-flex items-center gap-2.5 rounded-lg text-sm text-ink-soft transition hover:bg-sunken hover:text-ink', 'h-9 w-9 justify-center' => ! $label, 'px-3 py-2' => $label]) }}>
    <x-heroicon-o-squares-2x2 x-show="nav !== 'launcher'" x-cloak class="h-[18px] w-[18px]" />
    <x-heroicon-o-view-columns x-show="nav === 'launcher'" x-cloak class="h-[18px] w-[18px]" />
    @if ($label)
        <span x-text="nav === 'launcher' ? 'Use side menu' : 'Use full-screen menu'"></span>
    @endif
</button>
