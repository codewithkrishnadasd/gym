<form method="POST" action="{{ route('platform.organisations.update', $organisation) }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="section" value="features">

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
        description="Which parts of the application this organisation can use. Click a module to switch it on or off. Anything off is absent for them — no menu entry, no pages, no data shown elsewhere. Switching a module on brings in what it cannot work without.">
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
                            {{-- The tile is the control: on is the accent-tinted card,
                                 off the plain one. A disabled hidden input is not posted,
                                 so only the enabled keys reach the server. --}}
                            <input type="hidden" name="features[]" value="{{ $feature->value }}" :disabled="! has('{{ $feature->value }}')">
                            <button type="button" x-on:click="toggle('{{ $feature->value }}')"
                                :aria-pressed="has('{{ $feature->value }}')"
                                class="flex items-start gap-3 rounded-lg border p-3 text-left transition focus:outline-none focus:ring-2 focus:ring-accent/25"
                                :class="has('{{ $feature->value }}') ? 'border-accent bg-accent-soft' : 'border-hairline bg-surface opacity-80 hover:bg-raised hover:opacity-100'">
                                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg transition"
                                    :class="has('{{ $feature->value }}') ? 'bg-accent text-on-accent' : 'bg-sunken text-ink-muted'">
                                    <x-dynamic-component :component="'heroicon-o-'.$feature->icon()" class="h-4 w-4" />
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex items-center justify-between gap-2 text-sm font-medium text-ink">
                                        {{ $feature->label() }}
                                        <span class="text-[11px] font-semibold uppercase tracking-wide"
                                            :class="has('{{ $feature->value }}') ? 'text-accent-ink' : 'text-ink-muted'"
                                            x-text="has('{{ $feature->value }}') ? 'On' : 'Off'"></span>
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
                            </button>
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
