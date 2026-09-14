@php
    $tabs = ['overview' => 'Overview', 'plans' => 'Plans', 'attendance' => 'Attendance', 'payments' => 'Payments'];

    // Documents are a separate permission from the member record itself, so the
    // tab is absent rather than empty for staff who cannot open them.
    if (auth()->user()?->can('viewAny', \App\Models\Invoice::class)) {
        $tabs['billing'] = 'Billing';
    }

    if (auth()->user()?->can('viewAny', \App\Models\Document::class)) {
        $tabs['documents'] = 'Documents';
    }
    $today = \Illuminate\Support\Carbon::today($organisation->timezone);
@endphp

<div>
    <x-ui.flash />

    <x-ui.page-header :title="$member->name" :back="route('tenant.members.index')" :back-label="$organisation->term('member_plural')"
        :description="collect([$displayPhone, $member->primaryClub?->name])->filter()->join(' · ')">
        <x-slot:actions>
            @if ($whatsappUrl)
                <x-ui.button icon="chat-bubble-left-right" :href="$whatsappUrl" target="_blank" rel="noopener">WhatsApp</x-ui.button>
            @endif

            @can('create', \App\Models\FeePayment::class)
                <x-ui.button icon="banknotes" :href="route('tenant.finance.payments.create', ['member' => $member->id])" wire:navigate>
                    Collect fee
                </x-ui.button>
            @endcan

            @can('update', $member)
                <x-ui.button variant="primary" icon="pencil-square" :href="route('tenant.members.edit', $member)" wire:navigate>Edit</x-ui.button>
            @endcan

            @can('archive', $member)
                @if ($member->status->value === 'archived')
                    <x-ui.button icon="arrow-uturn-left" wire:click="restore">Restore</x-ui.button>
                @else
                    <x-ui.button variant="danger" icon="trash" wire:click="archive"
                        data-confirm-title="Remove this member?" data-confirm-action="Remove" data-confirm-tone="danger" data-confirm="Remove {{ $member->name }}? They stay in historical reports and can be restored.">Remove</x-ui.button>
                @endif
            @endcan
        </x-slot:actions>
    </x-ui.page-header>

    @if ($lifecycleError)
        <div class="mb-4"><x-ui.alert tone="critical">{{ $lifecycleError }}</x-ui.alert></div>
    @endif

    {{-- Mounted unconditionally so a message composed by a Livewire action on
         this page has a listener to reach. Rendered with no notification it
         draws nothing; keyed to the page, not the message, so the component
         survives from one action to the next. --}}
    @if ($canNotify)
        <div class="mb-4">
            <livewire:notifications.action-panel :notification-id="$notificationId"
                :key="'member-panel-'.$member->id" />
        </div>
    @endif

    {{-- Snapshot cards, always visible above the tabs. --}}
    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-ui.stat label="Status" :value="$member->status->label()" :tone="$member->status->tone()" icon="user-circle" />
        @php
            // Derived from the end date, not the stored status: nothing writes
            // "expired" when a term simply runs out, so a lapsed plan would
            // otherwise render as a healthy green card.
            $planHealth = \App\Enums\SubscriptionHealth::for($currentSubscription, $today);
        @endphp
        <x-ui.stat label="Current plan" :value="$currentSubscription?->plan->name ?? 'None'"
            :hint="$currentSubscription
                ? $planHealth->detailedLabel($currentSubscription, $today).' · '.$currentSubscription->end_date->format('d M Y')
                : 'no active plan'"
            :tone="$planHealth->tone()" icon="rectangle-stack" />
        <x-ui.stat label="Outstanding" :value="$organisation->money($outstanding)"
            :tone="$outstanding > 0 ? 'caution' : 'positive'" icon="exclamation-circle" />
        <x-ui.stat label="Attendance" :value="$attendanceRate.'%'" :hint="$presentMarks.' visits in 12 weeks'"
            :tone="$attendanceRate >= 60 ? 'positive' : 'neutral'" icon="clipboard-document-check" />
    </div>

    <x-ui.tabs :items="collect($tabs)->map(fn ($label, $key) => [
        'label' => $key === 'plans' ? 'Plans' : $label,
        'url' => route('tenant.members.show', ['member' => $member->id, 'tab' => $key]),
        'active' => $tab === $key,
    ])->values()->all()" />

    @if ($tab === 'overview')
        <div class="grid gap-5 lg:grid-cols-3">
            <x-ui.card class="lg:col-span-2" title="Details">
                <dl class="grid gap-x-6 sm:grid-cols-2">
                    <x-ui.definition label="Full name" :value="$member->name" />
                    <x-ui.definition label="WhatsApp number" :value="$displayPhone ?? '—'" />
                    <x-ui.definition label="Date of birth"
                        :value="$member->date_of_birth ? $member->date_of_birth->format('d M Y').' ('.(int) $member->date_of_birth->diffInYears($today).' yrs)' : '—'" />
                    <x-ui.definition label="Gender" :value="$member->gender ?: '—'" />
                    <x-ui.definition :label="$organisation->term('club_singular')" :value="$member->primaryClub?->name ?? 'Unassigned'" />
                    <x-ui.definition label="Joined" :value="$member->joined_at->format('d M Y')" />
                    <x-ui.definition label="Address" :value="$member->address['line1'] ?? '—'" />
                    @if ($member->notes)
                        <x-ui.definition class="sm:col-span-2" label="Notes" :value="$member->notes" />
                    @endif
                </dl>
            </x-ui.card>

            <x-ui.card :padded="false" :title="$organisation->term('club_singular').' history'">
                @if ($clubHistory->isEmpty())
                    <div class="px-4 py-5 text-sm text-ink-muted">
                        Always been with {{ $member->primaryClub?->name ?? 'no club' }}.
                    </div>
                @else
                    <ol class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($clubHistory as $entry)
                            <li class="px-4 py-3">
                                <p class="text-sm text-ink">
                                    {{ $entry->fromClub?->name ?? 'Unassigned' }} → <span class="font-medium">{{ $entry->toClub?->name }}</span>
                                </p>
                                <p class="numeric text-xs text-ink-muted">
                                    {{ $entry->changed_at->format('d M Y') }} · {{ $entry->changedBy?->user?->name ?? 'system' }}
                                </p>
                                @if ($entry->reason)
                                    <p class="mt-0.5 text-xs text-ink-soft">{{ $entry->reason }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </x-ui.card>
        </div>
    @elseif ($tab === 'plans')
        <div class="space-y-5">
            @can('createFor', [\App\Models\MemberSubscription::class, $member])
                <div class="flex justify-end">
                    <x-ui.button variant="primary" icon="plus" x-on:click="$dispatch('open-modal', 'start-plan')">
                        {{ $currentSubscription ? 'Renew plan' : 'Start plan' }}
                    </x-ui.button>
                </div>
            @endcan

            <x-ui.card :padded="false" title="Plan history" description="Renewals create a new term; past terms are never rewritten.">
                @if ($subscriptions->isEmpty())
                    <x-ui.empty icon="rectangle-stack" title="No plans yet"
                        :description="'Start a plan so '.$member->name.' can be billed and tracked.'" />
                @else
                    <x-ui.table>
                        <x-slot:head>
                            <x-ui.th>Plan</x-ui.th>
                            <x-ui.th>Term</x-ui.th>
                            <x-ui.th align="right">Due</x-ui.th>
                            <x-ui.th align="right">Paid</x-ui.th>
                            <x-ui.th>Status</x-ui.th>
                            <x-ui.th align="right"></x-ui.th>
                        </x-slot:head>

                        @foreach ($subscriptions as $subscription)
                            @php $due = max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor); @endphp
                            <tr class="transition hover:bg-raised">
                                <x-ui.td>
                                    <p class="font-medium text-ink">{{ $subscription->plan->name }}</p>
                                    <p class="text-xs text-ink-muted">{{ $subscription->club->name }}</p>
                                </x-ui.td>
                                <x-ui.td numeric class="whitespace-nowrap">
                                    {{ $subscription->start_date->format('d M Y') }} – {{ $subscription->end_date->format('d M Y') }}
                                </x-ui.td>
                                <x-ui.td align="right" numeric>{{ $organisation->money($subscription->amount_due_minor) }}</x-ui.td>
                                <x-ui.td align="right" numeric>
                                    {{ $organisation->money($subscription->amount_paid_minor) }}
                                    @if ($due > 0)
                                        <span class="block text-xs text-caution">{{ $organisation->money($due) }} due</span>
                                    @endif
                                </x-ui.td>
                                <x-ui.td>
                                    @php $health = $subscription->health($today); @endphp
                                    <x-ui.badge :tone="$health->tone()">{{ $health->label() }}</x-ui.badge>
                                    @if ($health === \App\Enums\SubscriptionHealth::ExpiringSoon)
                                        <span class="mt-0.5 block text-xs text-caution">
                                            {{ $health->detailedLabel($subscription, $today) }}
                                        </span>
                                    @endif
                                </x-ui.td>
                                <x-ui.td align="right">
                                    @can('changeStatus', $subscription)
                                        @if ($subscription->status->value === 'active')
                                            <div class="flex items-center justify-end gap-1">
                                                <x-ui.button size="sm" variant="ghost"
                                                    wire:click="changePlanStatus({{ $subscription->id }}, 'paused')">Pause</x-ui.button>
                                                <x-ui.button size="sm" variant="ghost"
                                                    wire:click="changePlanStatus({{ $subscription->id }}, 'cancelled')"
                                                    data-confirm-title="Cancel this plan?" data-confirm-action="Cancel plan" data-confirm-tone="danger" data-confirm="Cancel this plan? This cannot be undone.">Cancel</x-ui.button>
                                            </div>
                                        @elseif ($subscription->status->value === 'paused')
                                            <x-ui.button size="sm" variant="ghost"
                                                wire:click="changePlanStatus({{ $subscription->id }}, 'active')">Resume</x-ui.button>
                                        @endif
                                    @endcan
                                </x-ui.td>
                            </tr>
                        @endforeach
                    </x-ui.table>
                @endif
            </x-ui.card>
        </div>
    @elseif ($tab === 'attendance')
        <x-ui.card title="Last 12 weeks" :description="$presentMarks.' visits · '.$attendanceRate.'% of marked days'">
            {{-- A compact calendar heatmap: one column per week, one cell per day. --}}
            <div class="overflow-x-auto">
                <div class="flex gap-1" style="min-width: 640px">
                    @for ($week = 11; $week >= 0; $week--)
                        <div class="flex flex-1 flex-col gap-1">
                            @for ($day = 0; $day < 7; $day++)
                                @php
                                    $date = $today->copy()->subWeeks($week)->startOfWeek()->addDays($day);
                                    $record = $date->gt($today) ? null : $attendance->get($date->toDateString());
                                    $tone = match ($record?->action->value) {
                                        'present' => 'bg-positive',
                                        'late' => 'bg-caution',
                                        'excused' => 'bg-info',
                                        'absent' => 'bg-critical',
                                        default => 'bg-sunken',
                                    };
                                @endphp
                                <div class="h-4 flex-1 rounded-[3px] {{ $date->gt($today) ? 'opacity-30' : '' }} {{ $tone }}"
                                    title="{{ $date->format('D d M Y') }}{{ $record ? ' — '.$record->action->label() : '' }}"></div>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-ink-muted">
                @foreach (\App\Enums\AttendanceAction::cases() as $case)
                    <span class="flex items-center gap-1.5">
                        <span @class(['h-3 w-3 rounded-[3px]',
                            'bg-positive' => $case->value === 'present',
                            'bg-caution' => $case->value === 'late',
                            'bg-info' => $case->value === 'excused',
                            'bg-critical' => $case->value === 'absent',
                        ])></span>
                        {{ $case->label() }}
                    </span>
                @endforeach
                <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-[3px] bg-sunken"></span> Not marked</span>
            </div>
        </x-ui.card>
    @else
        <x-ui.card :padded="false" title="Payment ledger"
            :description="'Paid '.$organisation->money($totalPaid).' · '.$organisation->money($outstanding).' outstanding'">
            @if ($payments->isEmpty())
                <x-ui.empty icon="banknotes" title="No payments yet"
                    :description="'Fees collected from '.$member->name.' will appear here.'">
                    <x-slot:actions>
                        @can('create', \App\Models\FeePayment::class)
                            <x-ui.button size="sm" variant="primary" :href="route('tenant.finance.payments.create', ['member' => $member->id])" wire:navigate>
                                Collect fee
                            </x-ui.button>
                        @endcan
                    </x-slot:actions>
                </x-ui.empty>
            @else
                <x-ui.table>
                    <x-slot:head>
                        <x-ui.th>Date</x-ui.th>
                        <x-ui.th>Plan</x-ui.th>
                        <x-ui.th>Method</x-ui.th>
                        <x-ui.th>Collected by</x-ui.th>
                        <x-ui.th align="right">Amount</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                    </x-slot:head>

                    @foreach ($payments as $payment)
                        <tr class="transition hover:bg-raised">
                            <x-ui.td numeric class="whitespace-nowrap">
                                <a href="{{ route('tenant.finance.payments.show', $payment) }}" wire:navigate
                                    class="font-medium text-ink hover:text-accent">{{ $payment->payment_date->format('d M Y') }}</a>
                            </x-ui.td>
                            <x-ui.td>{{ $payment->subscription?->plan?->name ?? '—' }}</x-ui.td>
                            <x-ui.td>{{ $payment->payment_method->label() }}</x-ui.td>
                            <x-ui.td>{{ $payment->collectedBy?->user?->name ?? '—' }}</x-ui.td>
                            <x-ui.td align="right" numeric class="font-medium text-ink">{{ $organisation->money($payment->amount_minor) }}</x-ui.td>
                            <x-ui.td><x-ui.badge :tone="$payment->confirmation_status->tone()">{{ $payment->confirmation_status->label() }}</x-ui.badge></x-ui.td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @endif
        </x-ui.card>
    @endif

    @if ($tab === 'billing')
        @php
            $invoices = $member->invoices()->with('club:id,name')->orderByDesc('id')->get();
            $owed = $invoices->filter->isOpen()->sum(fn ($invoice) => $invoice->outstandingMinor());
        @endphp

        <div class="space-y-5">
            @can('create', \App\Models\Invoice::class)
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-ink-soft">
                        @if ($owed > 0)
                            <span class="font-medium text-caution">{{ $organisation->money($owed) }}</span> outstanding across open invoices.
                        @else
                            Nothing outstanding.
                        @endif
                    </p>
                    <x-ui.button variant="primary" icon="plus" :href="route('tenant.billing.create', ['member' => $member->id])" wire:navigate>New invoice</x-ui.button>
                </div>
            @endcan

            <x-ui.card :padded="false" title="Invoices" description="Bills for anything outside a plan. Payments can be made in parts.">
                @if ($invoices->isEmpty())
                    <x-ui.empty icon="document-text" title="No invoices" :description="'Nothing has been billed to '.$member->name.' outside their plan.'" />
                @else
                    <ul class="divide-y divide-[var(--c-hairline)]">
                        @foreach ($invoices as $invoice)
                            <li class="flex items-center justify-between gap-3 px-4 py-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('tenant.billing.show', $invoice) }}" wire:navigate class="font-mono text-sm font-medium text-ink hover:text-accent">{{ $invoice->number }}</a>
                                        <x-ui.badge :tone="$invoice->status->tone()">{{ $invoice->status->label() }}</x-ui.badge>
                                    </div>
                                    <p class="numeric text-xs {{ $invoice->isOverdue() ? 'text-critical' : 'text-ink-muted' }}">
                                        Issued {{ $invoice->issue_date->format('d M Y') }} · due {{ $invoice->due_date?->format('d M Y') ?? 'on receipt' }}
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="numeric text-sm font-semibold text-ink">{{ $organisation->money($invoice->total_minor) }}</p>
                                    @if ($invoice->isOpen() && $invoice->paid_minor > 0)
                                        <p class="numeric text-xs text-caution">{{ $organisation->money($invoice->outstandingMinor()) }} due</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card>
        </div>
    @endif

    @if ($tab === 'documents')
        <livewire:documents.panel subject-type="member" :subject-id="$member->id" :key="'documents-member-'.$member->id" />
    @endif

    @can('createFor', [\App\Models\MemberSubscription::class, $member])
        <x-ui.modal name="start-plan" :title="$currentSubscription ? 'Renew plan' : 'Start a plan'"
            description="A renewal begins the day after the current term ends, so consecutive terms never overlap.">
            <div class="space-y-4">
                <x-ui.select wire:model="planId" name="planId" label="Plan" required>
                    <option value="">Select a plan…</option>
                    @foreach ($availablePlans as $plan)
                        <option value="{{ $plan->id }}">
                            {{ $plan->name }} — {{ $organisation->money($plan->price_minor) }} / {{ $plan->duration_days }} days
                        </option>
                    @endforeach
                </x-ui.select>

                <x-ui.input wire:model="planStartDate" name="planStartDate" label="Start date" type="date"
                    hint="Leave as-is to start immediately, or after the current term for a renewal." />

                <x-ui.input wire:model="planAmount" name="planAmount" label="Amount due" inputmode="decimal"
                    :prefix="$organisation->currencySymbol()" placeholder="Plan price"
                    hint="Override only if you are applying a discount." />
            </div>

            <x-slot:footer>
                <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'start-plan')">Cancel</x-ui.button>
                <x-ui.button variant="primary" wire:click="startPlan" wire:loading.attr="disabled" wire:target="startPlan">
                    <span wire:loading.remove wire:target="startPlan">{{ $currentSubscription ? 'Renew plan' : 'Start plan' }}</span>
                    <span wire:loading wire:target="startPlan" class="inline-flex items-center gap-1.5"><x-ui.spinner /> Saving…</span>
                </x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    @endcan
</div>
