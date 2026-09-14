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
        ['label' => 'Expense categories', 'url' => route('tenant.settings.organisation', ['tab' => 'expenses']), 'active' => $tab === 'expenses'],
        ['label' => 'Billing', 'url' => route('tenant.settings.organisation', ['tab' => 'billing']), 'active' => $tab === 'billing'],
        ['label' => 'Storage', 'url' => route('tenant.settings.organisation', ['tab' => 'storage']), 'active' => $tab === 'storage'],
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

        {{-- Outside the profile form on purpose: picking a file saves straight
             away, so it must not wait on — or be lost by — a separate Save. --}}
        <div class="mt-5 grid gap-5 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <x-ui.card title="Logo and favicon"
                    description="Upload one picture. It becomes both the logo on your sign-in page and sidebar, and the small icon in the browser tab — resized and compressed for you.">

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                        <div class="shrink-0">
                            <p class="mb-2 text-xs font-medium uppercase tracking-wide text-ink-muted">Logo</p>
                            <div class="grid h-24 w-24 place-items-center overflow-hidden rounded-xl border border-hairline bg-sunken">
                                @if ($organisation->logoUrl())
                                    <img src="{{ $organisation->logoUrl() }}" alt="{{ $organisation->name }}"
                                        class="h-full w-full object-contain p-1.5">
                                @else
                                    <span class="font-[family-name:var(--font-display)] text-2xl font-bold text-ink-muted">
                                        {{ mb_strtoupper(mb_substr($organisation->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0">
                            <p class="mb-2 text-xs font-medium uppercase tracking-wide text-ink-muted">Browser tab</p>
                            {{-- Shown at the size it is actually used, because a
                                 favicon that reads fine at 96px often does not at 16. --}}
                            <div class="flex w-44 items-center gap-2 rounded-t-lg border border-b-0 border-hairline bg-sunken px-2.5 py-2">
                                @if ($organisation->faviconUrl())
                                    <img src="{{ $organisation->faviconUrl() }}" alt="" class="h-4 w-4 shrink-0 rounded-sm object-cover">
                                @else
                                    <span class="grid h-4 w-4 shrink-0 place-items-center rounded-sm bg-accent text-[9px] font-bold text-on-accent">
                                        {{ mb_strtoupper(mb_substr($organisation->name, 0, 1)) }}
                                    </span>
                                @endif
                                <span class="truncate text-xs text-ink-soft">{{ $organisation->name }}</span>
                            </div>
                            <div class="h-2 rounded-b-lg border border-hairline bg-app"></div>
                        </div>

                        <div class="flex-1">
                            <label for="brand-image"
                                class="flex cursor-pointer flex-col items-center justify-center gap-1.5 rounded-xl border border-dashed border-hairline-strong bg-sunken px-4 py-6 text-center transition hover:border-accent hover:bg-raised">
                                <x-heroicon-o-arrow-up-tray class="h-5 w-5 text-ink-muted" />
                                <span class="text-sm font-medium text-ink">Choose an image</span>
                                <span class="text-xs text-ink-muted">JPEG, PNG or GIF · up to 8&nbsp;MB · square works best</span>
                                <input id="brand-image" type="file" class="sr-only" wire:model="brandImage"
                                    accept="image/jpeg,image/png,image/gif">
                            </label>

                            <div wire:loading wire:target="brandImage"
                                class="mt-2 inline-flex items-center gap-1.5 text-sm text-ink-soft">
                                <x-ui.spinner /> Resizing…
                            </div>

                            @error('brandImage')
                                <p class="mt-2 text-sm text-critical">{{ $message }}</p>
                            @enderror

                            @if ($organisation->logo_path)
                                <x-ui.button size="sm" variant="ghost" icon="trash" class="mt-2"
                                    wire:click="removeBrandImage"
                                    data-confirm-title="Remove the logo?" data-confirm-action="Remove" data-confirm-tone="danger" data-confirm="Remove the logo and favicon? Your organisation's initial is shown instead.">
                                    Remove
                                </x-ui.button>
                            @endif
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <div>
                <x-ui.card title="What happens to it">
                    <ul class="space-y-2.5 text-sm text-ink-soft">
                        <li class="flex gap-2">
                            <x-heroicon-o-arrows-pointing-in class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                            Scaled down to fit {{ \App\Support\Images\BrandImage::LOGO_MAX_EDGE }}px and re-compressed,
                            so a phone photo of a sign becomes a few kilobytes.
                        </li>
                        <li class="flex gap-2">
                            <x-heroicon-o-scissors class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                            The favicon is cropped from the middle to a square, so upload something that reads well
                            centred.
                        </li>
                        <li class="flex gap-2">
                            <x-heroicon-o-swatch class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                            Transparency is kept. A logo with a transparent background stays transparent on both the
                            light and dark theme.
                        </li>
                    </ul>
                </x-ui.card>
            </div>
        </div>
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
    @elseif ($tab === 'expenses')
        <div class="grid gap-5 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <x-ui.card title="Expense categories"
                    description="What your expenses can be filed under. Every expense report groups by these, so keep them to the costs you actually want to see separately.">

                    <div class="flex flex-col gap-2 sm:flex-row">
                        <div class="flex-1">
                            <x-ui.input wire:model="newCategory" name="newCategory" label="Add a category"
                                placeholder="e.g. Trainer commission" wire:keydown.enter.prevent="addCategory" />
                        </div>
                        <div class="flex items-end">
                            <x-ui.button icon="plus" wire:click="addCategory" wire:target="addCategory">Add</x-ui.button>
                        </div>
                    </div>

                    @error('expenseCategories')
                        <p class="mt-3 text-sm text-critical">{{ $message }}</p>
                    @enderror

                    <ul class="mt-4 divide-y divide-[var(--c-hairline)] rounded-lg border border-hairline">
                        @forelse ($expenseCategories as $index => $category)
                            @php $used = $categoryUsage[$category['name']] ?? 0; @endphp

                            <li class="flex items-center justify-between gap-3 px-3 py-2">
                                <span class="flex min-w-0 items-center gap-2">
                                    <span @class([
                                        'truncate text-sm',
                                        'text-ink' => $category['active'],
                                        'text-ink-muted line-through' => ! $category['active'],
                                    ])>{{ $category['name'] }}</span>

                                    @if (! $category['active'])
                                        <x-ui.badge tone="neutral">Inactive</x-ui.badge>
                                    @endif

                                    @if ($used > 0)
                                        <span class="shrink-0 text-xs text-ink-muted">
                                            {{ $used }} {{ Str::plural('expense', $used) }}
                                        </span>
                                    @endif
                                </span>

                                <span class="flex shrink-0 items-center gap-1">
                                    <x-ui.button size="sm" variant="ghost"
                                        :icon="$category['active'] ? 'pause-circle' : 'play-circle'"
                                        wire:click="toggleCategory({{ $index }})">
                                        {{ $category['active'] ? 'Deactivate' : 'Reactivate' }}
                                    </x-ui.button>

                                    {{-- Deleting is offered only while nothing references it;
                                         otherwise deactivating is the only safe option. --}}
                                    @if ($used === 0)
                                        <x-ui.button size="sm" variant="ghost" icon="trash"
                                            wire:click="removeCategory({{ $index }})">Delete</x-ui.button>
                                    @endif
                                </span>
                            </li>
                        @empty
                            <li class="px-3 py-6 text-center text-sm text-ink-muted">
                                No categories yet — add at least one before saving.
                            </li>
                        @endforelse
                    </ul>
                </x-ui.card>
            </div>

            <div class="space-y-5">
                <x-ui.card title="How these are used">
                    <ul class="space-y-2.5 text-sm text-ink-soft">
                        <li class="flex gap-2">
                            <x-heroicon-o-banknotes class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                            Offered when recording an expense, and as the category filter on the expenses list.
                        </li>
                        <li class="flex gap-2">
                            <x-heroicon-o-chart-bar class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                            Used to group spending in finance reports.
                        </li>
                        <li class="flex gap-2">
                            <x-heroicon-o-archive-box class="mt-0.5 h-4 w-4 shrink-0 text-ink-muted" />
                            Deactivating stops a category appearing on new expenses. Everything already filed under it
                            stays in your filters and reports. A category with expenses behind it cannot be deleted at
                            all — only deactivated.
                        </li>
                    </ul>
                </x-ui.card>

                <x-ui.card title="Save">
                    <x-ui.button variant="primary" size="lg" class="w-full" wire:click="saveExpenseCategories"
                        wire:loading.attr="disabled" wire:target="saveExpenseCategories">
                        <span wire:loading.remove wire:target="saveExpenseCategories">Save categories</span>
                        <span wire:loading wire:target="saveExpenseCategories" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                    </x-ui.button>

                    <x-ui.button variant="ghost" class="mt-2 w-full" wire:click="restoreDefaultCategories"
                        data-confirm-title="Add the default categories?" data-confirm-action="Add defaults" data-confirm-tone="accent" data-confirm="Add back any missing default categories? Nothing you already have is changed, and nothing is saved until you press Save categories.">
                        Add default categories
                    </x-ui.button>
                </x-ui.card>
            </div>
        </div>
    @elseif ($tab === 'billing')
        <livewire:settings.billable-items />
    @elseif ($tab === 'storage')
        <livewire:settings.storage-buckets />
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
                                    x-on:click="copied = await window.copyToClipboard('{{ '{'.$name.'}' }}'); setTimeout(() => copied = false, 1500)">
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
                                data-confirm-title="Reset this template?" data-confirm-action="Reset wording" data-confirm-tone="danger" data-confirm="Reset this template to the built-in wording?">
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
