@php
    $actionLabel = fn (string $value): string => \Illuminate\Support\Str::of($value)->replace('_', ' ')->ucfirst();

    $groups = collect($actionTypes)->groupBy(fn ($type) => str_starts_with($type->value, 'user_') ? 'Staff actions' : 'Member actions');
@endphp

<div>
    <x-ui.flash />

    <x-ui.page-header title="Settings" :description="$organisation->name" />

    <x-ui.tabs :items="[
        ['label' => 'Profile', 'url' => route('tenant.settings.organisation', ['tab' => 'profile']), 'active' => $tab === 'profile'],
        ['label' => 'Terminology', 'url' => route('tenant.settings.organisation', ['tab' => 'terminology']), 'active' => $tab === 'terminology'],
        ['label' => 'Notifications', 'url' => route('tenant.settings.organisation', ['tab' => 'notifications']), 'active' => $tab === 'notifications'],
        ['label' => 'Message templates', 'url' => route('tenant.settings.organisation', ['tab' => 'templates']), 'active' => $tab === 'templates'],
    ]" />

    @if ($tab === 'profile')
        <form wire:submit="saveProfile" class="grid gap-5 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <x-ui.card title="Organisation profile" description="Shown on sign-in pages, receipts, and messages.">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input class="sm:col-span-2" wire:model="name" name="name" label="Organisation name" required />
                        <x-ui.input wire:model="contactEmail" name="contactEmail" label="Contact email" type="email" />
                        <x-ui.input wire:model.live.debounce.500ms="contactPhone" name="contactPhone" label="Contact phone"
                            :hint="$phonePreview ? 'Will be sent as '.$phonePreview : null" />
                        <x-ui.input class="sm:col-span-2" wire:model="addressLine" name="addressLine" label="Address" />

                        <x-ui.select wire:model="timezone" name="timezone" label="Timezone" required
                            hint="Decides which day attendance and payments belong to.">
                            @foreach ($timezones as $zone)
                                <option value="{{ $zone }}">{{ $zone }}</option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.select wire:model.live="defaultCountryCode" name="defaultCountryCode" label="Default country"
                            required hint="Used to build WhatsApp links from local numbers.">
                            @foreach ($countries as $code => $dialling)
                                <option value="{{ $code }}">{{ $code }} ({{ $dialling }})</option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.input wire:model="locale" name="locale" label="Locale" required
                            hint="Controls date and currency formatting, e.g. en, en-IN." />

                        <x-ui.field label="Currency" hint="Currency is set by the platform administrator.">
                            <input type="text" disabled value="{{ $organisation->currency_code }} ({{ $organisation->currencySymbol() }})"
                                class="min-h-[40px] w-full cursor-not-allowed rounded-lg border border-hairline-strong bg-sunken px-3 py-2 text-sm text-ink-muted">
                        </x-ui.field>
                    </div>
                </x-ui.card>
            </div>

            <div>
                <x-ui.card title="Save">
                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full" wire:loading.attr="disabled" wire:target="saveProfile">
                        <span wire:loading.remove wire:target="saveProfile">Save profile</span>
                        <span wire:loading wire:target="saveProfile" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>
                </x-ui.card>
            </div>
        </form>
    @elseif ($tab === 'terminology')
        <form wire:submit="saveTerminology" class="grid gap-5 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <x-ui.card title="Terminology"
                    description="Rename what your organisation calls members, staff, and clubs. Labels change everywhere — navigation, headings, buttons, exports — without touching any stored data.">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input wire:model.live.debounce.400ms="memberSingular" name="memberSingular" label="Member (singular)" required placeholder="Member" />
                        <x-ui.input wire:model.live.debounce.400ms="memberPlural" name="memberPlural" label="Member (plural)" required placeholder="Members" />
                        <x-ui.input wire:model.live.debounce.400ms="userSingular" name="userSingular" label="Staff (singular)" required placeholder="Staff" />
                        <x-ui.input wire:model.live.debounce.400ms="userPlural" name="userPlural" label="Staff (plural)" required placeholder="Staff" />
                        <x-ui.input wire:model.live.debounce.400ms="clubSingular" name="clubSingular" label="Club (singular)" required placeholder="Club" />
                        <x-ui.input wire:model.live.debounce.400ms="clubPlural" name="clubPlural" label="Club (plural)" required placeholder="Clubs" />
                    </div>
                </x-ui.card>
            </div>

            <div class="space-y-5">
                <x-ui.card title="Preview">
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2 text-ink-soft">
                            <x-heroicon-o-user-group class="h-4 w-4 text-ink-muted" /> {{ $memberPlural ?: 'Members' }}
                        </li>
                        <li class="flex items-center gap-2 text-ink-soft">
                            <x-heroicon-o-identification class="h-4 w-4 text-ink-muted" /> {{ $userPlural ?: 'Staff' }}
                        </li>
                        <li class="flex items-center gap-2 text-ink-soft">
                            <x-heroicon-o-building-office-2 class="h-4 w-4 text-ink-muted" /> {{ $clubPlural ?: 'Clubs' }}
                        </li>
                    </ul>

                    <p class="mt-3 rounded-lg bg-raised px-3 py-2 text-xs text-ink-muted">
                        “Add {{ $memberSingular ?: 'Member' }}” · “Invite {{ $userSingular ?: 'Staff' }}” ·
                        “New {{ $clubSingular ?: 'Club' }}”
                    </p>
                </x-ui.card>

                <x-ui.card title="Save">
                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full" wire:loading.attr="disabled" wire:target="saveTerminology">
                        <span wire:loading.remove wire:target="saveTerminology">Save terminology</span>
                        <span wire:loading wire:target="saveTerminology" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>
                </x-ui.card>
            </div>
        </form>
    @elseif ($tab === 'templates')
        <form wire:submit="saveTemplate" class="grid gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                <x-ui.card title="Message template"
                    description="The wording used when this action notifies someone. Variables in braces are filled in automatically when the message is generated.">
                    <div class="space-y-4">
                        <x-ui.select wire:model.live="templateAction" name="templateAction" label="Action">
                            @foreach ($actionTypes as $type)
                                <option value="{{ $type->value }}">{{ $actionLabel($type->value) }}</option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.field label="Message" name="templateBody" for="f-templateBody"
                            hint="Any line whose only variable has no value is dropped automatically, so optional details never leave a half-empty line.">
                            <textarea id="f-templateBody" wire:model.live.debounce.500ms="templateBody" rows="10"
                                class="w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 font-mono text-[13px] leading-relaxed text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25"></textarea>
                        </x-ui.field>
                    </div>
                </x-ui.card>

                <x-ui.card title="Preview" description="Rendered with example values.">
                    <pre class="whitespace-pre-wrap rounded-lg border border-hairline bg-raised px-3 py-2.5 font-sans text-[13px] leading-relaxed text-ink-soft">{{ $templatePreview }}</pre>
                </x-ui.card>
            </div>

            <div class="space-y-5">
                <x-ui.card title="Available variables"
                    description="Click to copy. Only these are recognised for this action.">
                    <ul class="space-y-2">
                        @foreach ($templateVariables as $name => $description)
                            <li x-data="{ copied: false }">
                                <button type="button" class="w-full text-left"
                                    x-on:click="navigator.clipboard.writeText('{{ '{'.$name.'}' }}').then(() => { copied = true; setTimeout(() => copied = false, 1500) })">
                                    <code class="font-mono text-xs text-accent">{{ '{'.$name.'}' }}</code>
                                    <span x-show="copied" x-cloak class="ml-1 text-[11px] text-positive">copied</span>
                                    <span class="block text-xs text-ink-muted">{{ $description }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>

                <x-ui.card title="Save">
                    <div class="flex flex-col gap-2">
                        <x-ui.button type="submit" variant="primary" size="lg" wire:loading.attr="disabled" wire:target="saveTemplate">
                            <span wire:loading.remove wire:target="saveTemplate">Save template</span>
                            <span wire:loading wire:target="saveTemplate" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                        </x-ui.button>

                        @if ($templateIsCustom)
                            <x-ui.button type="button" variant="ghost" wire:click="resetTemplate"
                                wire:confirm="Reset this template to the built-in wording?">
                                Reset to default
                            </x-ui.button>
                        @endif
                    </div>
                </x-ui.card>

                <x-ui.alert tone="info" title="Already-sent messages">
                    Editing a template only affects messages generated from now on. Every message already sent keeps
                    the wording it was sent with.
                </x-ui.alert>
            </div>
        </form>
    @else
        <form wire:submit="saveNotifications" class="grid gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                <x-ui.card title="WhatsApp action notifications"
                    description="After an admin action succeeds, the app prepares a message you can review and send. It never sends anything on its own.">
                    <div class="space-y-1 rounded-lg border border-hairline bg-raised p-1">
                        <x-ui.checkbox wire:model.live="notificationsEnabled" label="Enable action notifications"
                            description="Turn this off to stop preparing messages entirely." />
                        <x-ui.checkbox wire:model="requirePreview" label="Always preview before opening WhatsApp"
                            description="Recommended. The message is shown for review before the link is available." />
                    </div>
                </x-ui.card>

                @foreach ($groups as $groupName => $types)
                    <x-ui.card :title="$groupName" :padded="false">
                        <div @class(['divide-y divide-[var(--c-hairline)]', 'pointer-events-none opacity-50' => ! $notificationsEnabled])>
                            @foreach ($types as $type)
                                <div class="px-3 py-1">
                                    <x-ui.checkbox wire:model="enabledActions" value="{{ $type->value }}"
                                        :label="$actionLabel($type->value)"
                                        :description="str_contains($type->value, 'attendance') ? 'Off by default — daily marking can generate very high message volume.' : null" />
                                </div>
                            @endforeach
                        </div>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="space-y-5">
                <x-ui.card title="Save">
                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full" wire:loading.attr="disabled" wire:target="saveNotifications">
                        <span wire:loading.remove wire:target="saveNotifications">Save notifications</span>
                        <span wire:loading wire:target="saveNotifications" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>
                </x-ui.card>

                <x-ui.alert tone="info" title="What is never sent">
                    Messages never contain passwords, invitation tokens, full bank details, or internal audit data.
                </x-ui.alert>
            </div>
        </form>
    @endif
</div>
