<x-layouts.app :heading="$organisation->name">
    <x-ui.page-header :title="$organisation->name" :back="route('platform.organisations.index')" back-label="Organisations"
        :description="$organisation->slug" />

    {{-- The console's own tabs first, then every tab the organisation's admins
         have on their settings page — the same component, acting on this
         organisation (App\Support\SettingsTabs). --}}
    <x-ui.tabs :items="$tabs" />

    {{-- The settings component shows its own flash on its tabs. --}}
    @if (\App\Support\SettingsTabs::isPlatformTab($tab))
        <x-ui.flash />
    @endif

    {{-- Shown exactly once, straight after it is issued; never stored in plain
         text and never written to the audit event (MEP.md 5.13). --}}
    @if (session('reset_link'))
        {{-- The link lives in x-data on this wrapper rather than inline on the
             button: @js() inside an x-component's attribute makes Livewire's
             morph-aware Blade compiler build a regex too large for PCRE.

             copy() uses the window helper because the clipboard API is absent
             outside a secure context, where calling it directly threw. --}}
        <div class="mb-4" x-data="{
            copied: false,
            copyFailed: false,
            link: @js(session('reset_link')),
            async copy() {
                this.copied = await window.copyToClipboard(this.link);
                this.copyFailed = ! this.copied;

                setTimeout(() => { this.copied = false; this.copyFailed = false; }, 3000);
            },
        }">
            <x-ui.alert tone="caution" title="Password link for {{ session('reset_link_for') }}">
                <p class="mt-2 select-all break-all rounded-md bg-surface px-3 py-2 font-mono text-[13px] text-ink">
                    {{ session('reset_link') }}
                </p>

                <div class="mt-2 flex flex-wrap items-center gap-2">
                    <x-ui.button type="button" size="sm" icon="clipboard-document" x-on:click="copy()">
                        <span x-show="! copied">Copy link</span>
                        <span x-show="copied" x-cloak class="text-positive">Copied</span>
                    </x-ui.button>

                    <span x-show="copyFailed" x-cloak class="text-xs text-caution">
                        Copying was blocked — select the link above and copy it by hand.
                    </span>
                </div>

                <p class="mt-2 text-xs">
                    Send this to them however you normally reach them. It expires in
                    {{ session('reset_link_expires') }}, works once, and their current password keeps working until
                    they use it — so nobody is locked out in the meantime.
                </p>
            </x-ui.alert>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4">
            <x-ui.alert tone="critical" title="Please fix the following">
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        </div>
    @endif
    @if (\App\Support\SettingsTabs::isPlatformTab($tab))
        <div class="{{ in_array($tab, ['domains', 'people'], true) ? 'max-w-3xl' : 'max-w-4xl' }}">
            @include('platform.organisations.tabs.'.$tab)
        </div>
    @else
        <livewire:settings.organisation-settings :tab="$tab" :platform-organisation-id="$organisation->id" />
    @endif
</x-layouts.app>
