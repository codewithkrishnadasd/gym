<form method="POST" action="{{ route('platform.organisations.update', $organisation) }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="section" value="appearance">

    @php
        // The accent is the palette's light "Primary" colour. An
        // organisation that set an accent before the palette existed
        // sees it here as that colour and keeps it on save.
        $overrides = \App\Support\Theme\ThemeTokens::sanitize(old('theme', $organisation->theme_colors ?? []));

        if (! isset($overrides['light']['accent']) && $organisation->accent_color) {
            $overrides['light']['accent'] = $organisation->accent_color;
        }

        $accent = $overrides['light']['accent'] ?? \App\Support\Theme\AccentPalette::DEFAULT_ACCENT;
        $themeState = [
            'defaults' => \App\Support\Theme\ThemeTokens::DEFAULTS,
            'derived' => \App\Support\Theme\ThemeTokens::derivedFromAccent($accent),
            'theme' => [
                'light' => $overrides['light'] ?? [],
                'dark' => $overrides['dark'] ?? [],
            ],
            'pairs' => \App\Support\Theme\ThemeTokens::CONTRAST_PAIRS,
            'keys' => \App\Support\Theme\ThemeTokens::keys(),
        ];
    @endphp

    <x-ui.card title="Appearance"
        description="Every colour the application uses on this organisation's domains, for the light and dark themes separately. The light theme's primary colour is the brand accent: tints and the text on it are derived from it on save unless set here.">

        {{-- State lives on a plain div: @js() inside an x-component
             attribute is not compiled by Livewire's Blade pass. --}}
        <div x-data="{
            ...@js($themeState),
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
    </x-ui.card>

    <x-ui.button type="submit" variant="primary" size="lg">Save changes</x-ui.button>
</form>
