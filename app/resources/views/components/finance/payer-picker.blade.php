@props([
    'organisation',
    'selectedMember' => null,
    'results',
    'memberSearch' => '',
    'walkIn' => false,
    'payerName' => '',
    'payerPhone' => '',
    /** What the walk-in is for: "paying" or "billing". */
    'verb' => 'paying',
])

{{--
    Who a payment or invoice is for: a member found by search, or — for a
    visitor, a guest, a company — a person named directly. The two forms
    share this so the two flows never drift apart.
--}}
@php
    $memberLabel = strtolower($organisation->term('member_singular'));
    $membersLabel = strtolower($organisation->term('member_plural'));
@endphp

@if ($selectedMember)
    <div class="flex items-center justify-between gap-3 rounded-lg border border-hairline bg-raised p-3">
        <div class="flex min-w-0 items-center gap-3">
            <x-ui.avatar :name="$selectedMember->name" tone="accent" />
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-ink">{{ $selectedMember->name }}</p>
                <p class="truncate text-xs text-ink-muted">
                    {{ $selectedMember->phone }}@if ($organisation->usesClubs()) &middot; {{ $selectedMember->primaryClub?->name ?? 'No club' }}@endif
                </p>
            </div>
        </div>
        <x-ui.button size="sm" variant="ghost" wire:click="clearMember" type="button">Change</x-ui.button>
    </div>

    @error('memberId')<p class="mt-2 text-xs text-critical">{{ $message }}</p>@enderror
@elseif ($walkIn)
    <div class="mb-3 flex items-center justify-between gap-3 rounded-lg border border-hairline bg-raised px-3 py-2">
        <p class="text-sm text-ink-soft">
            <span class="font-medium text-ink">Not a {{ $memberLabel }}</span> — the {{ $verb === 'billing' ? 'invoice' : 'receipt' }} is made out to the name below.
        </p>
        <x-ui.button size="sm" variant="ghost" wire:click="clearMember" type="button">Choose a {{ $memberLabel }} instead</x-ui.button>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <x-ui.input wire:model.live.debounce.400ms="payerName" name="payerName" label="Name" required placeholder="Full name" autofocus />
        <x-ui.input wire:model="payerPhone" name="payerPhone" label="WhatsApp number" inputmode="tel" placeholder="Optional"
            hint="With a number, the message can be sent to them as usual." />
    </div>
@else
    <x-ui.field label="Search {{ $memberLabel }}" name="memberId"
        hint="Type at least 2 characters of a name or phone number.">
        <div class="relative">
            <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" />
            <input type="search" wire:model.live.debounce.300ms="memberSearch" placeholder="Name or phone number…"
                class="min-h-[44px] w-full rounded-lg border border-hairline-strong bg-surface py-2 pl-9 pr-3 text-sm text-ink placeholder:text-ink-muted focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
            <div wire:loading wire:target="memberSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted"><x-ui.spinner size="xs" /></div>
        </div>
    </x-ui.field>

    @if ($results->isNotEmpty())
        <ul class="mt-2 divide-y divide-[var(--c-hairline)] overflow-hidden rounded-lg border border-hairline">
            @foreach ($results as $result)
                <li>
                    <button type="button" wire:click="selectMember({{ $result->id }})"
                        class="flex w-full items-center gap-3 p-3 text-left transition hover:bg-raised">
                        <x-ui.avatar :name="$result->name" size="sm" />
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-medium text-ink">{{ $result->name }}</span>
                            <span class="block truncate text-xs text-ink-muted">{{ $result->phone }}@if ($organisation->usesClubs()) &middot; {{ $result->primaryClub?->name ?? 'No club' }}@endif</span>
                        </span>
                    </button>
                </li>
            @endforeach
        </ul>
    @elseif (mb_strlen($memberSearch) >= 2)
        <p class="mt-2 text-sm text-ink-muted">No {{ $membersLabel }} match “{{ $memberSearch }}”.</p>
    @endif

    {{-- The other way in: someone who has no member record at all. --}}
    <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-hairline pt-3 text-sm text-ink-muted">
        <span>Not a {{ $memberLabel }}?</span>
        <button type="button" wire:click="startWalkIn"
            class="inline-flex items-center gap-1.5 font-medium text-accent hover:underline">
            <x-heroicon-o-user-plus class="h-4 w-4" />
            {{ $verb === 'billing' ? 'Bill' : 'Collect from' }} someone by name
        </button>
    </div>
@endif
