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
                $overrides = \App\Support\Theme\ThemeTokens::sanitize(old('theme', $organisation->theme_colors ?? []));
                $derived = \App\Support\Theme\ThemeTokens::derivedFromAccent($accent);
                $themeState = [
                    'accent' => $accent,
                    'defaults' => \App\Support\Theme\ThemeTokens::DEFAULTS,
                    'derived' => $derived,
                    'theme' => [
                        'light' => $overrides['light'] ?? [],
                        'dark' => $overrides['dark'] ?? [],
                    ],
                    'pairs' => \App\Support\Theme\ThemeTokens::CONTRAST_PAIRS,
                    'keys' => \App\Support\Theme\ThemeTokens::keys(),
                ];
            @endphp

            <x-ui.card title="Appearance"
                description="How the application looks on this organisation's domains. Start from one accent colour; open the full palette to set any colour for the light and dark themes separately.">

                {{-- State lives on a plain div: @js() inside an x-component
                     attribute is not compiled by Livewire's Blade pass. --}}
                <div x-data="{
                    ...@js($themeState),
                    mode: 'light',
                    advanced: {{ $overrides === [] ? 'false' : 'true' }},
                    value(theme, key) {
                        return this.theme[theme][key] || this.derived[theme][key] || this.defaults[theme][key];
                    },
                    isOverridden(theme, key) {
                        return Boolean(this.theme[theme][key]);
                    },
                    reset(theme, key) {
                        delete this.theme[theme][key];
                        this.theme[theme] = { ...this.theme[theme] };
                    },
                    vars(theme) {
                        return this.keys.map((key) => `--c-${key}:${this.value(theme, key)}`).join(';');
                    },
                    luminance(hex) {
                        const v = hex.replace('#', '');
                        if (v.length !== 6) return 0;
                        const lin = [0, 2, 4].map((i) => {
                            const c = parseInt(v.slice(i, i + 2), 16) / 255;
                            return c <= 0.03928 ? c / 12.92 : ((c + 0.055) / 1.055) ** 2.4;
                        });
                        return 0.2126 * lin[0] + 0.7152 * lin[1] + 0.0722 * lin[2];
                    },
                    contrast(a, b) {
                        const l1 = this.luminance(a), l2 = this.luminance(b);
                        return (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);
                    },
                    warnings(theme) {
                        return this.pairs
                            .map(([fg, bg, label]) => ({ label, ratio: this.contrast(this.value(theme, fg), this.value(theme, bg)) }))
                            .filter((pair) => pair.ratio < 4.5);
                    },
                }" class="space-y-5">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                        <div class="shrink-0">
                            <x-ui.field label="Accent colour" for="accent_color" name="accent_color">
                                <div class="flex items-center gap-2">
                                    <input type="color" id="accent_color" name="accent_color" x-model="accent"
                                        class="h-10 w-14 cursor-pointer rounded-lg border border-hairline-strong bg-surface p-1">
                                    <input type="text" x-model="accent" aria-label="Accent colour hex"
                                        class="numeric min-h-[40px] w-28 rounded-lg border border-hairline-strong bg-surface px-3 py-2 font-mono text-sm uppercase text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
                                </div>
                            </x-ui.field>
                        </div>

                        <div class="flex-1 space-y-2">
                            <div class="flex flex-wrap items-center gap-2 rounded-lg border border-hairline bg-surface p-3">
                                <span class="text-xs font-medium uppercase tracking-wide text-ink-muted">Light</span>
                                <span class="rounded-lg px-3 py-1.5 text-sm font-medium"
                                    :style="`background:${accent};color:{{ $derived['light']['on-accent'] ?? '#ffffff' }}`">Button</span>
                                <span class="rounded-md px-2 py-0.5 text-xs font-medium"
                                    style="background: {{ $derived['light']['accent-soft'] ?? '#ecfdff' }}; color: {{ $derived['light']['accent-ink'] ?? '#0e7490' }}">Badge</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 rounded-lg border border-hairline p-3" style="background:#080d18">
                                <span class="text-xs font-medium uppercase tracking-wide" style="color:#6f7f99">Dark</span>
                                <span class="rounded-lg px-3 py-1.5 text-sm font-medium"
                                    style="background: {{ $derived['dark']['accent'] ?? '#22d3ee' }}; color: {{ $derived['dark']['on-accent'] ?? '#04212b' }}">Button</span>
                                <span class="rounded-md px-2 py-0.5 text-xs font-medium"
                                    style="background: {{ $derived['dark']['accent-soft'] ?? '#0c3441' }}; color: {{ $derived['dark']['accent-ink'] ?? '#67e8f9' }}">Badge</span>
                            </div>

                            <p class="text-xs text-ink-muted">
                                Buttons, links, and active navigation. Tints and the text colour on the accent are derived
                                automatically for both themes and refresh on save; anything set in the full palette below
                                overrides the derived value.
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-hairline pt-4">
                        <button type="button" x-on:click="advanced = !advanced"
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-accent hover:underline">
                            <x-heroicon-o-chevron-right class="h-4 w-4 transition" x-bind:class="advanced && 'rotate-90'" />
                            <span x-text="advanced ? 'Hide full palette' : 'Full palette — every colour, light and dark'"></span>
                        </button>

                        <div x-show="advanced" x-cloak class="mt-4 space-y-5">
                            {{-- Previews come first: the operator judges the change
                                 where it lands, not in a list of hex codes. --}}
                            <div class="grid gap-3 lg:grid-cols-2">
                                @foreach (['light' => 'Light theme', 'dark' => 'Dark theme'] as $themeKey => $themeLabel)
                                    <div class="overflow-hidden rounded-xl border" :style="vars('{{ $themeKey }}')"
                                        style="border-color: var(--c-hairline-strong)">
                                        <div class="flex items-center justify-between px-4 py-2 text-xs font-medium uppercase tracking-wide"
                                            style="background: var(--c-sunken); color: var(--c-ink-muted); border-bottom: 1px solid var(--c-hairline)">
                                            {{ $themeLabel }}
                                            <span x-show="warnings('{{ $themeKey }}').length" class="normal-case tracking-normal" style="color: var(--c-critical)">
                                                Low contrast
                                            </span>
                                        </div>
                                        <div class="space-y-3 p-4" style="background: var(--c-app); color: var(--c-ink)">
                                            <div class="rounded-lg border p-3" style="background: var(--c-surface); border-color: var(--c-hairline)">
                                                <p class="text-sm font-semibold">{{ $organisation->name }}</p>
                                                <p class="text-xs" style="color: var(--c-ink-soft)">42 members · 3 renewals due</p>
                                                <p class="mt-1 text-xs" style="color: var(--c-ink-muted)">Updated a minute ago</p>
                                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                                    <span class="rounded-lg px-3 py-1.5 text-sm font-medium" style="background: var(--c-accent); color: var(--c-on-accent)">Primary</span>
                                                    <span class="rounded-lg border px-3 py-1.5 text-sm font-medium" style="background: var(--c-button-secondary); color: var(--c-button-secondary-ink); border-color: var(--c-button-secondary-border)">Secondary</span>
                                                    <span class="text-sm font-medium" style="color: var(--c-accent)">Link</span>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 text-xs font-medium">
                                                <span class="rounded-md px-2 py-0.5" style="background: var(--c-accent-soft); color: var(--c-accent-ink)">Accent</span>
                                                <span class="rounded-md px-2 py-0.5" style="background: var(--c-positive-soft); color: var(--c-positive)">Paid</span>
                                                <span class="rounded-md px-2 py-0.5" style="background: var(--c-caution-soft); color: var(--c-caution)">Expiring</span>
                                                <span class="rounded-md px-2 py-0.5" style="background: var(--c-critical-soft); color: var(--c-critical)">Overdue</span>
                                                <span class="rounded-md px-2 py-0.5" style="background: var(--c-info-soft); color: var(--c-info)">Info</span>
                                            </div>
                                            <ul class="space-y-1 text-xs" style="color: var(--c-critical)">
                                                <template x-for="warning in warnings('{{ $themeKey }}')" :key="warning.label">
                                                    <li x-text="`${warning.label}: contrast ${warning.ratio.toFixed(1)}:1, below 4.5:1`"></li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-hairline">
                                <table class="w-full min-w-[560px] text-sm">
                                    <thead class="bg-sunken text-left text-xs font-medium uppercase tracking-wide text-ink-muted">
                                        <tr>
                                            <th class="px-3 py-2">Colour</th>
                                            <th class="px-3 py-2">Light</th>
                                            <th class="px-3 py-2">Dark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--c-hairline)]">
                                        @foreach (\App\Support\Theme\ThemeTokens::GROUPS as $group => $tokens)
                                            <tr class="bg-raised">
                                                <th colspan="3" class="px-3 py-1.5 text-left text-xs font-semibold text-ink-soft">{{ $group }}</th>
                                            </tr>
                                            @foreach ($tokens as $key => $label)
                                                <tr>
                                                    <td class="px-3 py-2 text-ink">{{ $label }}</td>
                                                    @foreach (['light', 'dark'] as $themeKey)
                                                        <td class="px-3 py-1.5">
                                                            <div class="flex items-center gap-1.5">
                                                                {{-- The picker shows the effective colour; the hidden
                                                                     field posts only a deliberate override, so
                                                                     untouched tokens keep tracking the defaults. --}}
                                                                <input type="color" :value="value('{{ $themeKey }}', '{{ $key }}')"
                                                                    x-on:input="theme['{{ $themeKey }}']['{{ $key }}'] = $event.target.value"
                                                                    aria-label="{{ $label }}, {{ $themeKey }} theme"
                                                                    class="h-8 w-10 shrink-0 cursor-pointer rounded-md border border-hairline-strong bg-surface p-0.5">
                                                                <input type="hidden" name="theme[{{ $themeKey }}][{{ $key }}]" :value="theme['{{ $themeKey }}']['{{ $key }}'] ?? ''">
                                                                <span class="numeric w-[4.5rem] font-mono text-xs uppercase"
                                                                    :class="isOverridden('{{ $themeKey }}', '{{ $key }}') ? 'text-ink' : 'text-ink-muted'"
                                                                    x-text="value('{{ $themeKey }}', '{{ $key }}')"></span>
                                                                <button type="button" x-show="isOverridden('{{ $themeKey }}', '{{ $key }}')"
                                                                    x-on:click="reset('{{ $themeKey }}', '{{ $key }}')"
                                                                    class="rounded p-1 text-ink-muted hover:bg-sunken hover:text-ink" title="Back to default">
                                                                    <x-heroicon-o-arrow-uturn-left class="h-3.5 w-3.5" />
                                                                </button>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <p class="text-xs text-ink-muted">
                                Grey values follow the built-in palette or the accent; dark values are ones set here. Status
                                colours carry meaning across the app — change them only if the defaults clash with the brand.
                            </p>
                        </div>
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

            @php
                $enabledFeatures = \App\Enums\Feature::expand((array) old('features', $organisation->enabledFeatures()));
                $featureRequirements = collect(\App\Enums\Feature::cases())
                    ->mapWithKeys(fn ($feature) => [$feature->value => array_map(fn ($required) => $required->value, $feature->requires())])
                    ->all();
                $featureLabels = collect(\App\Enums\Feature::cases())
                    ->mapWithKeys(fn ($feature) => [$feature->value => $feature->label()])
                    ->all();
            @endphp

            <x-ui.card title="Features"
                description="Which parts of the application this organisation can use. Anything unticked is absent for them — no menu entry, no pages, no data shown elsewhere. Ticking a module ticks what it cannot work without.">
                {{-- State lives on a plain div: @js() inside an x-component
                     attribute is not compiled by Livewire's Blade pass. --}}
                <div x-data="{
                    enabled: @js($enabledFeatures),
                    requires: @js($featureRequirements),
                    labels: @js($featureLabels),
                    has(key) {
                        return this.enabled.includes(key);
                    },
                    neededBy(key) {
                        return Object.entries(this.requires)
                            .filter(([dependent, needs]) => needs.includes(key) && this.has(dependent))
                            .map(([dependent]) => dependent);
                    },
                    toggle(key) {
                        if (this.has(key)) {
                            if (this.neededBy(key).length) return;
                            this.enabled = this.enabled.filter((k) => k !== key);
                            return;
                        }
                        const add = (k) => {
                            if (this.enabled.includes(k)) return;
                            this.enabled.push(k);
                            (this.requires[k] ?? []).forEach(add);
                        };
                        add(key);
                    },
                }" class="space-y-5">
                    @foreach (\App\Enums\Feature::grouped() as $group => $features)
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-ink-muted">{{ $group }}</p>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach ($features as $feature)
                                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border p-3 transition"
                                        :class="has('{{ $feature->value }}') ? 'border-accent/40 bg-accent-soft/40' : 'border-hairline bg-surface hover:bg-raised'">
                                        <input type="checkbox" name="features[]" value="{{ $feature->value }}"
                                            :checked="has('{{ $feature->value }}')"
                                            x-on:click.prevent="toggle('{{ $feature->value }}')"
                                            class="mt-0.5 h-4 w-4 shrink-0 rounded border-hairline-strong text-accent focus:ring-accent/25">
                                        <span class="min-w-0 flex-1">
                                            <span class="flex items-center gap-1.5 text-sm font-medium text-ink">
                                                <x-dynamic-component :component="'heroicon-o-'.$feature->icon()" class="h-4 w-4 text-ink-muted" />
                                                {{ $feature->label() }}
                                            </span>
                                            <span class="mt-0.5 block text-xs text-ink-muted">{{ $feature->description() }}</span>
                                            @if ($feature->requires() !== [])
                                                <span class="mt-1 block text-[11px] text-ink-soft">
                                                    Needs {{ collect($feature->requires())->map(fn ($required) => $required->label())->join(' and ') }}
                                                </span>
                                            @endif
                                            <span x-show="has('{{ $feature->value }}') && neededBy('{{ $feature->value }}').length" x-cloak class="mt-1 block text-[11px] text-caution"
                                                x-text="'Kept on for ' + neededBy('{{ $feature->value }}').map((k) => labels[k]).join(', ')"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <p class="text-xs text-ink-muted">
                        Switching a module off hides it; nothing already recorded is deleted, and switching it back on
                        brings the data back.
                    </p>
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
