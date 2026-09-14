<x-layouts.app :heading="$organisation->name">
    <x-ui.page-header :title="$organisation->name" :back="route('platform.organisations.index')" back-label="Organisations"
        :description="$organisation->slug" />

    <x-ui.flash />

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

    <div class="grid max-w-5xl gap-5 lg:grid-cols-3">
        <form method="POST" action="{{ route('platform.organisations.update', $organisation) }}" class="space-y-5 lg:col-span-2">
            @csrf
            @method('PUT')

            <x-ui.card title="General">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input name="name" id="name" label="Name" value="{{ old('name', $organisation->name) }}" required />
                    <x-ui.input name="slug" id="slug" label="Slug" value="{{ old('slug', $organisation->slug) }}" required />

                    <x-ui.select name="status" id="status" label="Status">
                        @foreach (\App\Enums\OrganisationStatus::cases() as $case)
                            <option value="{{ $case->value }}" @selected(old('status', $organisation->status->value) === $case->value)>
                                {{ $case->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.field label="Timezone" for="timezone" name="timezone">
                        <input id="timezone" name="timezone" type="text" list="timezone-options" required
                            value="{{ old('timezone', $organisation->timezone) }}"
                            class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">
                        <datalist id="timezone-options">
                            @foreach ($timezones as $timezone)
                                <option value="{{ $timezone }}"></option>
                            @endforeach
                        </datalist>
                    </x-ui.field>

                    <x-ui.input name="currency_code" id="currency_code" label="Currency code" maxlength="3" required
                        value="{{ old('currency_code', $organisation->currency_code) }}" class="uppercase"
                        hint="ISO 4217, e.g. INR, USD, GBP." />

                    <x-ui.input name="locale" id="locale" label="Locale" required
                        value="{{ old('locale', $organisation->locale) }}" hint="Date and currency formatting." />

                    <x-ui.input name="contact_email" id="contact_email" label="Contact email" type="email"
                        value="{{ old('contact_email', $organisation->contact_email) }}" />

                    <x-ui.input name="contact_phone" id="contact_phone" label="Contact phone"
                        value="{{ old('contact_phone', $organisation->contact_phone) }}" />
                </div>
            </x-ui.card>

            @php
                $accent = old('accent_color', $organisation->accent_color) ?: \App\Support\Theme\AccentPalette::DEFAULT_ACCENT;
                $preview = \App\Support\Theme\AccentPalette::for($accent);
            @endphp

            <x-ui.card title="Accent colour"
                description="The one colour this organisation is branded with — buttons, links, active navigation. Surfaces, text, and the green/amber/red status colours stay fixed, because those carry meaning.">

                <div x-data="{ accent: @js($accent) }" class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    <div class="shrink-0">
                        <x-ui.field label="Colour" for="accent_color" name="accent_color">
                            <div class="flex items-center gap-2">
                                <input type="color" id="accent_color" name="accent_color" x-model="accent"
                                    class="h-10 w-14 cursor-pointer rounded-lg border border-hairline-strong bg-surface p-1">
                                <input type="text" x-model="accent" aria-label="Accent colour hex"
                                    class="numeric min-h-[40px] w-28 rounded-lg border border-hairline-strong bg-surface px-3 py-2 font-mono text-sm uppercase text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
                            </div>
                        </x-ui.field>
                    </div>

                    {{-- Live swatches, so the choice is judged against both
                         themes rather than discovered after saving. --}}
                    <div class="flex-1 space-y-2">
                        <div class="flex flex-wrap items-center gap-2 rounded-lg border border-hairline bg-surface p-3">
                            <span class="text-xs font-medium uppercase tracking-wide text-ink-muted">Light</span>
                            <span class="rounded-lg px-3 py-1.5 text-sm font-medium"
                                :style="`background:${accent};color:{{ $preview['light']['--c-on-accent'] }}`">Button</span>
                            <span class="rounded-md px-2 py-0.5 text-xs font-medium"
                                style="background: {{ $preview['light']['--c-accent-soft'] }}; color: {{ $preview['light']['--c-accent-ink'] }}">Badge</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 rounded-lg border border-hairline p-3" style="background:#080d18">
                            <span class="text-xs font-medium uppercase tracking-wide" style="color:#6f7f99">Dark</span>
                            <span class="rounded-lg px-3 py-1.5 text-sm font-medium"
                                style="background: {{ $preview['dark']['--c-accent'] }}; color: {{ $preview['dark']['--c-on-accent'] }}">Button</span>
                            <span class="rounded-md px-2 py-0.5 text-xs font-medium"
                                style="background: {{ $preview['dark']['--c-accent-soft'] }}; color: {{ $preview['dark']['--c-accent-ink'] }}">Badge</span>
                        </div>

                        <p class="text-xs text-ink-muted">
                            Save to refresh the dark preview. Text colour on the accent is chosen automatically for
                            contrast, so a pale colour gets dark text rather than unreadable white.
                        </p>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card title="Terminology"
                description="How this organisation refers to members, staff, and clubs throughout the application. Labels only — no stored data changes.">
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        'terminology_member_singular' => 'Member (singular)',
                        'terminology_member_plural' => 'Member (plural)',
                        'terminology_user_singular' => 'Staff (singular)',
                        'terminology_user_plural' => 'Staff (plural)',
                        'terminology_club_singular' => 'Club (singular)',
                        'terminology_club_plural' => 'Club (plural)',
                    ] as $field => $label)
                        <x-ui.input :name="$field" :id="$field" :label="$label" required
                            value="{{ old($field, $organisation->$field) }}" />
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.button type="submit" variant="primary" size="lg">Save changes</x-ui.button>
        </form>

        <div class="space-y-5">
            <x-ui.card title="Domains" description="A hostname can belong to only one organisation.">
                <div class="space-y-2">
                    @foreach ($organisation->domains as $domain)
                        <div class="rounded-lg border border-hairline bg-raised p-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-sm text-ink">{{ $domain->hostname }}</span>
                                @if ($domain->is_primary)
                                    <x-ui.badge tone="accent" :dot="false">Primary</x-ui.badge>
                                @endif
                                <x-ui.badge :tone="$domain->status->tone()">{{ $domain->status->label() }}</x-ui.badge>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('platform.organisations.domains.update', [$organisation, $domain]) }}"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status" onchange="this.form.requestSubmit()" aria-label="Domain status"
                                        class="min-h-[36px] rounded-lg border border-hairline-strong bg-surface px-2 text-xs text-ink-soft focus:border-accent focus:outline-none">
                                        @foreach (\App\Enums\DomainStatus::cases() as $case)
                                            <option value="{{ $case->value }}" @selected($domain->status->value === $case->value)>
                                                {{ $case->label() }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @unless ($domain->is_primary)
                                        <input type="hidden" name="is_primary" value="0">
                                        <x-ui.button type="submit" name="is_primary" value="1" size="sm">Make primary</x-ui.button>
                                    @endunless
                                </form>

                                @if ($organisation->domains->count() > 1)
                                    <form method="POST" action="{{ route('platform.organisations.domains.destroy', [$organisation, $domain]) }}">
                                        @csrf
                                        @method('DELETE')
                                        {{-- The confirmation is on the button, not the form: the
                                             interceptor replays the click, which submits it. --}}
                                        <x-ui.button type="submit" size="sm" variant="danger"
                                            data-confirm-title="Remove this domain?"
                                            data-confirm-action="Remove domain"
                                            data-confirm="{{ $domain->hostname }} will stop working for sign-in immediately.">Remove</x-ui.button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('platform.organisations.domains.store', $organisation) }}"
                    class="mt-4 space-y-3 border-t border-hairline pt-4">
                    @csrf
                    <x-ui.input name="hostname" id="new_hostname" label="Add domain" placeholder="club.example.com" />

                    <x-ui.select name="status" id="new_status" label="Status">
                        @foreach (\App\Enums\DomainStatus::cases() as $case)
                            <option value="{{ $case->value }}" @selected($case === \App\Enums\DomainStatus::Active)>{{ $case->label() }}</option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.button type="submit" variant="primary" class="w-full">Add domain</x-ui.button>
                </form>
            </x-ui.card>

            <x-ui.card title="People" description="Accounts with a membership in this organisation.">
                <div class="space-y-2">
                    @forelse ($organisation->organisationUsers as $membership)
                        <div class="rounded-lg border border-hairline bg-raised p-3">
                            <div class="flex items-start gap-2.5">
                                <x-ui.avatar :name="$membership->user->name" size="sm" />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-ink">{{ $membership->user->name }}</p>
                                    <p class="numeric truncate text-xs text-ink-muted">{{ $membership->user->phone }}</p>
                                </div>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                <x-ui.badge :tone="$membership->role->value === 'admin' ? 'accent' : 'neutral'" :dot="false">
                                    {{ $membership->role->value === 'admin' ? 'Administrator' : 'Staff' }}
                                </x-ui.badge>
                                <x-ui.badge :tone="$membership->status->tone()">{{ $membership->status->label() }}</x-ui.badge>
                            </div>

                            <form method="POST" action="{{ route('platform.organisations.members.reset-password', [$organisation, $membership]) }}"
                                class="mt-2">
                                @csrf
                                <x-ui.button type="submit" size="sm" icon="key" class="w-full"
                                    data-confirm-title="Create a password link?"
                                    data-confirm-action="Create link"
                                    data-confirm-tone="accent"
                                    data-confirm="Any earlier link for {{ $membership->user->name }} stops working. Their current password keeps working until they use this one.">Create password link</x-ui.button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-ink-muted">No people yet.</p>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.app>
