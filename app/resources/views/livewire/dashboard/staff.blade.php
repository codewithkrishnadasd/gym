<div>
    <x-ui.flash />

    <x-ui.page-header :title="'Hello, '.\Illuminate\Support\Str::before($membership->user?->name ?? '', ' ')"
        :description="$organisation->usesClubs() && $clubs->isNotEmpty() ? $organisation->name.' · '.$clubs->pluck('name')->join(', ', ' and ') : $organisation->name" />

    <x-ui.period-filter :presets="$presets" :range="$range" :from="$from" :to="$to" :today="\Illuminate\Support\Carbon::today($organisation->timezone)->toDateString()" :clubs="$organisation->usesClubs() ? $clubs : null" :club-label="$organisation->term('club_plural')" />

    @php $quickAction = \App\Support\Navigation::quickAction($organisation, auth()->user()); @endphp
    @if ($quickAction)
        <x-ui.fab :href="route($quickAction['route'])" :label="$quickAction['label']" :symbol="$quickAction['symbol']" />
    @endif

    @php
        $hasPlans = $organisation->hasFeature('plans');
        $hasAttendance = $organisation->hasFeature('attendance') && $organisation->hasFeature('members');
    @endphp

    {{-- Quick actions: only what this user is actually permitted to do. --}}
    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @if ($canMarkAttendance)
            <a href="{{ route('tenant.attendance.members') }}" wire:navigate
                class="flex min-h-[76px] flex-col justify-between rounded-xl border border-hairline bg-surface p-4 elevate transition hover:border-accent hover:elevate-lg">
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-accent-soft text-accent">
                    <x-heroicon-o-clipboard-document-check class="h-4.5 w-4.5" />
                </span>
                <span class="mt-2 text-sm font-medium text-ink">Mark attendance</span>
            </a>
        @endif

        @if ($canCollectFees)
            <a href="{{ route('tenant.finance.payments.create') }}" wire:navigate
                class="flex min-h-[76px] flex-col justify-between rounded-xl border border-hairline bg-surface p-4 elevate transition hover:border-accent hover:elevate-lg">
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-positive-soft text-positive">
                    <x-heroicon-o-banknotes class="h-4.5 w-4.5" />
                </span>
                <span class="mt-2 text-sm font-medium text-ink">Collect a fee</span>
            </a>
        @endif

        @if ($canViewMembers)
            <a href="{{ route('tenant.members.index') }}" wire:navigate
                class="flex min-h-[76px] flex-col justify-between rounded-xl border border-hairline bg-surface p-4 elevate transition hover:border-accent hover:elevate-lg">
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-info-soft text-info">
                    <x-heroicon-o-magnifying-glass class="h-4.5 w-4.5" />
                </span>
                <span class="mt-2 text-sm font-medium text-ink">Find a {{ strtolower($organisation->term('member_singular')) }}</span>
            </a>
        @endif

        @if ($hasAttendance)
        <div class="flex min-h-[76px] flex-col justify-between rounded-xl border border-hairline bg-surface p-4 elevate">
            <span class="text-[13px] font-medium text-ink-soft">Today's attendance</span>
            <span class="numeric font-[family-name:var(--font-display)] text-xl font-semibold">
                {{ $todaysAttendance }}<span class="text-sm font-normal text-ink-muted">/{{ $rosterSize }}</span>
            </span>
        </div>
        @endif
    </div>

    @if ($canCollectFees)
        <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <x-ui.stat label="You collected" :value="$organisation->moneyCompact($confirmedTotal)" icon="check-circle"
                tone="positive" hint="confirmed, all time" :href="$links['ownConfirmed']" wire:navigate />
            <x-ui.stat label="Awaiting confirmation" :value="$organisation->moneyCompact($pendingTotal)" icon="clock"
                :tone="$pendingCount > 0 ? 'caution' : 'neutral'" :hint="$pendingCount.' submitted'" :href="$links['ownPending']" wire:navigate />
            <x-ui.stat label="Rejected" :value="number_format($rejectedCount)" icon="x-circle"
                :tone="$rejectedCount > 0 ? 'critical' : 'neutral'" hint="need follow-up" :href="$links['ownRejected']" wire:navigate />
            <x-ui.stat :label="strtolower($organisation->term('member_plural')).' on your roster'" :value="number_format($rosterSize)"
                icon="user-group" :href="$links['roster']" wire:navigate />
        </div>
    @endif

    <div class="grid gap-3 lg:grid-cols-2">
        @if ($hasPlans)
        <x-ui.card :padded="false" title="Needs follow-up" description="Expiring soon or carrying a balance.">
            @if ($followUps->isEmpty())
                <x-ui.empty icon="check-circle" title="All clear"
                    :description="'No '.strtolower($organisation->term('member_plural')).' need chasing right now.'" />
            @else
                <ul class="divide-y divide-[var(--c-hairline)]">
                    @foreach ($followUps as $subscription)
                        @php $due = max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor); @endphp
                        <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-ink">{{ $subscription->member->name }}</p>
                                <p class="truncate text-xs text-ink-muted">
                                    {{ $subscription->plan->name }} · expires {{ $subscription->end_date->format('d M Y') }}
                                    @if ($subscription->member->phone) · {{ $subscription->member->phone }} @endif
                                </p>
                            </div>
                            @if ($due > 0)
                                <p class="numeric shrink-0 text-sm font-medium text-caution">{{ $organisation->money($due) }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
        @endif

        @if ($canCollectFees)
            <x-ui.card :padded="false" title="Your recent collections" description="Including their confirmation status.">
                <x-slot:actions>
                    <x-ui.button size="sm" variant="ghost" :href="route('tenant.finance.payments.index')" wire:navigate>See all</x-ui.button>
                </x-slot:actions>

                @if ($ownCollections->isEmpty())
                    <x-ui.empty icon="banknotes" title="Nothing collected in this period"
                        description="Fees you collect will be listed here with their status.">
                        <x-slot:actions>
                            <x-ui.button size="sm" variant="primary" :href="route('tenant.finance.payments.create')" wire:navigate>
                                Collect a fee
                            </x-ui.button>
                        </x-slot:actions>
                    </x-ui.empty>
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($ownCollections as $payment)
                            <li class="flex items-center justify-between gap-3 px-4 py-2.5">
                                <div class="min-w-0">
                                    <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate
                                        class="block truncate text-sm font-medium text-ink hover:text-accent">{{ $payment->payerName() }}</a>
                                    <p class="numeric truncate text-xs text-ink-muted">
                                        {{ $payment->payment_date->format('d M') }}@if ($payment->club) · {{ $payment->club->name }}@endif
                                    </p>
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    <p class="numeric text-sm font-medium">{{ $organisation->money($payment->amount_minor) }}</p>
                                    <x-ui.badge :tone="$payment->confirmation_status->tone()" :dot="false">
                                        {{ $payment->confirmation_status->label() }}
                                    </x-ui.badge>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        @endif
    </div>
</div>
