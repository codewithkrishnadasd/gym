<div>
    <x-ui.flash />

    <x-ui.page-header title="Navigation" description="Arrange the phone tab bar and the dashboard button the way you work. This is yours alone — nobody else's changes." />

        <form wire:submit="save" class="grid gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                <x-ui.card title="Phone tab bar"
                    :description="'Up to '.\App\Livewire\Account\NavigationSettings::MAX_TABS.' tabs along the bottom on a phone, in order, each with its icon. The Menu tab is always there beside them.'">
                    <div class="space-y-3">
                        @foreach ($mobileTabs as $index => $tab)
                            <div class="flex flex-col gap-2 rounded-lg border border-hairline bg-raised p-3 sm:flex-row sm:items-end" wire:key="tab-{{ $index }}-{{ $tab['route'] }}">
                                <span class="grid h-9 w-9 shrink-0 place-items-center self-start rounded-full bg-accent-soft font-[family-name:var(--font-display)] text-sm font-semibold text-accent-ink sm:self-end">{{ $index + 1 }}</span>
                                <div class="min-w-0 flex-1">
                                    <x-ui.select wire:model.live="mobileTabs.{{ $index }}.route" :name="'mobileTabs.'.$index.'.route'" label="Destination" required>
                                        @foreach ($destinations as $route => $label)
                                            <option value="{{ $route }}">{{ $label }}</option>
                                        @endforeach
                                    </x-ui.select>
                                </div>
                                <div class="flex items-end gap-2 sm:w-56">
                                    <div class="min-w-0 flex-1">
                                        <x-ui.select wire:model.live="mobileTabs.{{ $index }}.icon" :name="'mobileTabs.'.$index.'.icon'" label="Icon">
                                            <option value="">Default</option>
                                            @foreach ($icons as $icon => $iconLabel)
                                                <option value="{{ $icon }}">{{ $iconLabel }}</option>
                                            @endforeach
                                        </x-ui.select>
                                    </div>
                                    @if ($tab['icon'] !== '' && isset($icons[$tab['icon']]))
                                        <span class="mb-0.5 grid h-10 w-10 shrink-0 place-items-center rounded-lg border border-hairline bg-surface text-ink-soft">
                                            <x-dynamic-component :component="'heroicon-o-'.$tab['icon']" class="h-5 w-5" />
                                        </span>
                                    @endif
                                    <x-ui.button type="button" size="icon" variant="ghost" wire:click="removeTab({{ $index }})" :aria-label="'Remove tab '.($index + 1)" title="Remove" class="mb-0.5 text-ink-muted hover:text-critical">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </x-ui.button>
                                </div>
                            </div>
                        @endforeach

                        @if ($mobileTabs === [])
                            <p class="text-sm text-ink-muted">No tabs yet — the bar shows only Menu.</p>
                        @endif

                        @if (count($mobileTabs) < \App\Livewire\Account\NavigationSettings::MAX_TABS)
                            <x-ui.button type="button" variant="secondary" icon="plus" wire:click="addTab" wire:loading.attr="disabled" wire:target="addTab">
                                Add a tab
                            </x-ui.button>
                        @else
                            <p class="text-xs text-ink-muted">That is the most the bar takes. Remove one to add another.</p>
                        @endif
                    </div>
                </x-ui.card>

                <x-ui.card title="Dashboard button" description="The round button in the corner of your dashboard.">
                    <x-ui.select wire:model="quickAction" name="quickAction" label="Opens">
                        <option value="">Built-in order</option>
                        @foreach ($quickActions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </x-ui.select>
                </x-ui.card>
            </div>

            <div class="space-y-5">
                <x-ui.card title="Preview" description="How the bar reads on a phone.">
                    <div class="glass-nav grid grid-cols-5 rounded-[1.375rem] px-1 py-1">
                        @foreach (collect($mobileTabs)->filter(fn ($tab) => $tab['route'] !== '')->take(4) as $tab)
                            <span class="flex min-h-[54px] flex-col items-center justify-center gap-0.5 text-[11px] font-medium text-ink-muted">
                                <span class="grid h-7 w-12 place-items-center rounded-full">
                                    <x-dynamic-component :component="'heroicon-o-'.($tab['icon'] !== '' && isset($icons[$tab['icon']]) ? $tab['icon'] : 'squares-2x2')" class="h-5 w-5" />
                                </span>
                                <span class="max-w-full truncate px-1">{{ $destinations[$tab['route']] ?? '' }}</span>
                            </span>
                        @endforeach
                        <span class="flex min-h-[54px] flex-col items-center justify-center gap-0.5 text-[11px] font-medium text-ink-muted">
                            <span class="grid h-7 w-12 place-items-center rounded-full"><x-heroicon-o-ellipsis-horizontal-circle class="h-5 w-5" /></span>
                            <span>Menu</span>
                        </span>
                    </div>
                </x-ui.card>

                <x-ui.card title="Save">
                    <div class="flex flex-col gap-2">
                        <x-ui.button type="submit" variant="primary" size="lg" class="w-full" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">Save navigation</span>
                            <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                        </x-ui.button>
                        @if ($isCustom)
                            <x-ui.button type="button" variant="ghost" class="w-full" wire:click="reset_">Back to the built-in arrangement</x-ui.button>
                        @endif
                    </div>
                </x-ui.card>
            </div>
        </form>
</div>
