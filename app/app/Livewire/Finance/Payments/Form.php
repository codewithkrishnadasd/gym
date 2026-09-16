<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Payments;

use App\Actions\Payments\RecordFeePayment;
use App\Actions\Subscriptions\CreateSubscription;
use App\Enums\Feature;
use App\Enums\FinancialAccountStatus;
use App\Enums\FinancialAccountType;
use App\Enums\InvoiceStatus;
use App\Enums\MemberStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentPurpose;
use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Plan;
use App\Support\Money;
use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Fee collection (MEP.md 6.8).
 *
 * A staff user's submission always becomes a pending record they cannot
 * confirm; an admin may tick "confirm now", which still routes through
 * ConfirmFeePayment so the subscription credit, audit event, and notification
 * snapshot are produced by exactly one code path.
 */
class Form extends Component
{
    use ResolvesMembership;

    public string $memberSearch = '';

    public ?int $memberId = null;

    /**
     * A payer who is not a member — a day visitor, a guest, a company. The
     * payment then names them directly and can only settle an invoice raised
     * for them, or nothing in particular.
     */
    public bool $walkIn = false;

    public string $payerName = '';

    public string $payerPhone = '';

    public ?int $subscriptionId = null;

    /** An invoice this payment settles, in full or in part. */
    public ?int $invoiceId = null;

    /**
     * What the money is for, as one choice: "admission", "invoice:{id}",
     * "plan:{id}" or "other". One payment settles one thing, so a single
     * control replaces separate plan and invoice pickers.
     */
    public string $target = 'other';

    public string $amount = '';

    /** Taken off what is owed alongside the amount handed over. */
    public string $discount = '';

    /**
     * Whether to put earlier unlinked money towards this payment's target,
     * and how much. Only offered while the member has such credit and the
     * payment is for something.
     */
    public bool $useCredit = false;

    public string $creditAmount = '';

    public string $paymentMethod = 'cash';

    public ?int $financialAccountId = null;

    public string $transactionReference = '';

    public string $paymentDate = '';

    public string $notes = '';

    public bool $confirmImmediately = false;

    /**
     * Starting or renewing a plan from here, when the member has none or
     * theirs has run out: the term is created and then paid for in one
     * sitting, without a detour through the member's page.
     */
    public ?int $planId = null;

    public string $planStartDate = '';

    public string $planDiscount = '';

    public function mount(?int $member = null): void
    {
        $this->authorize('create', FeePayment::class);

        // "Collect fee" on a member's page links here with ?member=<id>. That
        // is a query parameter, not a route segment, so Livewire does not pass
        // it to mount() and the picker opened empty.
        $member ??= request()->integer('member') ?: null;

        $this->paymentDate = Carbon::today($this->organisation()->timezone)->toDateString();
        $this->confirmImmediately = $this->currentMembership()->isAdmin();
        $this->applyMethodAccount();

        // Without the Members module every payer is named directly.
        if (! $this->organisation()->hasFeature(Feature::Members)) {
            $this->startWalkIn('');
        } elseif ($member !== null) {
            $this->selectMember($member);
        }

        $invoice = request()->integer('invoice') ?: null;

        if ($invoice !== null) {
            // "Collect" on a walk-in invoice: the payer is whoever it names.
            $walkInInvoice = $this->memberId === null
                ? Invoice::query()->whereNull('member_id')->find($invoice)
                : null;

            if ($walkInInvoice) {
                $this->startWalkIn($walkInInvoice->payer_name ?? '', $walkInInvoice->payer_phone ?? '');
            }

            $this->selectInvoice($invoice);
        }

        if (request()->query('for') === 'admission' && $this->memberId !== null) {
            $this->selectTarget('admission');
        }

        // Arriving from a plan start or renewal: that term is the thing to pay.
        $subscription = request()->integer('subscription') ?: null;

        if ($subscription !== null && $this->memberId !== null) {
            $this->selectTarget('plan:'.$subscription);
        }
    }

    /**
     * Applies a choice from the "this payment is for" control: sets the
     * matching link, and defaults the amount to what is still owed on it.
     */
    public function selectTarget(string $target): void
    {
        $this->discount = '';
        $this->useCredit = false;
        $this->creditAmount = '';

        $this->applyTarget($target);

        // Unlinked money is used first: as much of the amount as the credit
        // can cover comes from it, and only the rest is collected now.
        if ($this->target !== 'other' && $this->availableCredit() > 0) {
            $this->useCredit = true;
            $this->refillCredit();
        }
    }

    private function applyTarget(string $target): void
    {
        if ($target === 'admission' && $this->admissionOutstanding() > 0) {
            $this->target = 'admission';
            $this->subscriptionId = null;
            $this->invoiceId = null;
            $this->amount = $this->major($this->admissionOutstanding());

            return;
        }

        if (str_starts_with($target, 'invoice:')) {
            $this->selectInvoice((int) substr($target, 8));

            if ($this->invoiceId !== null) {
                $this->target = $target;

                return;
            }
        }

        if (str_starts_with($target, 'plan:')) {
            $subscription = $this->subscriptionsForMember()->firstWhere('id', (int) substr($target, 5));

            if ($subscription) {
                $this->target = $target;
                $this->subscriptionId = $subscription->id;
                $this->invoiceId = null;
                $this->amount = $this->major($subscription->outstandingMinor());

                return;
            }
        }

        $this->target = 'other';
        $this->subscriptionId = null;
        $this->invoiceId = null;
    }

    public function updatedTarget(string $value): void
    {
        $this->selectTarget($value);
    }

    /**
     * Ticking "use unlinked money" proposes the most it can cover: whatever is
     * still owed after the amount and discount, up to the credit available.
     */
    public function updatedUseCredit(bool $value): void
    {
        if (! $value) {
            $this->creditAmount = '';

            return;
        }

        $this->refillCredit();
    }

    /**
     * The credit follows the amount: whatever is being paid, the unlinked
     * money covers as much of it as it can and the rest is collected now.
     */
    public function updatedAmount(): void
    {
        if ($this->useCredit) {
            $this->refillCredit();
        }
    }

    /**
     * How much of the amount is covered by unlinked money: the whole amount
     * when there is enough credit, otherwise everything that is available.
     */
    private function refillCredit(): void
    {
        $this->creditAmount = $this->major(min($this->amountMinor(), $this->availableCredit()));
    }

    private function amountMinor(): int
    {
        return Money::parseMajor($this->amount !== '' ? $this->amount : '0', $this->organisation()->currency_code)->minor ?? 0;
    }

    /**
     * Money actually changing hands now: the amount less what unlinked credit
     * covers. Shown on the form so the desk knows what to take.
     */
    public function receivedNowMinor(): int
    {
        $credit = $this->useCredit
            ? (Money::parseMajor($this->creditAmount !== '' ? $this->creditAmount : '0', $this->organisation()->currency_code)->minor ?? 0)
            : 0;

        return max(0, $this->amountMinor() - $credit);
    }

    private function availableCredit(): int
    {
        if ($this->memberId === null) {
            return 0;
        }

        return Member::query()->find($this->memberId)?->unlinkedCreditMinor() ?? 0;
    }

    private function major(int $minor): string
    {
        return (string) Money::ofMinor($minor, $this->organisation()->currency_code)->major();
    }

    private function admissionOutstanding(): int
    {
        if ($this->memberId === null) {
            return 0;
        }

        /** @var Member|null $member */
        $member = Member::query()->find($this->memberId);

        return $member?->admissionOutstandingMinor() ?? 0;
    }

    /**
     * What is still owed on the chosen target, or null when there is no
     * balance to measure against ("other").
     */
    private function targetOutstanding(): ?int
    {
        if ($this->target === 'admission') {
            return $this->admissionOutstanding();
        }

        if ($this->invoiceId !== null) {
            return $this->openInvoicesForMember()->firstWhere('id', $this->invoiceId)?->outstandingMinor();
        }

        if ($this->subscriptionId !== null) {
            return $this->subscriptionsForMember()->firstWhere('id', $this->subscriptionId)?->outstandingMinor();
        }

        return null;
    }

    /**
     * Chooses an invoice to pay against and defaults the amount to what is
     * still owed on it. Choosing an invoice also clears any plan selection:
     * one payment settles one thing.
     */
    public function selectInvoice(int $invoiceId): void
    {
        $invoice = $this->openInvoicesForMember()->firstWhere('id', $invoiceId);

        // Not this member's, or no longer open: drop it rather than leave an
        // id in place that save() would only reject later.
        if (! $invoice) {
            $this->invoiceId = null;

            return;
        }

        $this->invoiceId = $invoice->id;
        $this->subscriptionId = null;
        $this->target = 'invoice:'.$invoice->id;
        $this->amount = $this->major($invoice->outstandingMinor());
    }

    public function selectMember(int $memberId): void
    {
        if (! $this->organisation()->hasFeature(Feature::Members)) {
            return;
        }

        // Looked up directly, within the acting user's reach: the search list
        // is capped at a handful of rows, so a member further down the
        // alphabet would otherwise fail to prefill when arriving by link.
        $member = $this->selectableMembers()->find($memberId);

        if (! $member) {
            return;
        }

        $this->memberId = $member->id;
        $this->memberSearch = '';

        $this->invoiceId = null;

        // Admission comes first while it is owed — it is the one thing a new
        // member certainly has to pay — then the current plan.
        if ($this->admissionOutstanding() > 0) {
            $this->selectTarget('admission');

            return;
        }

        $subscription = $this->subscriptionsForMember()->first();

        $this->selectTarget($subscription ? 'plan:'.$subscription->id : 'other');
    }

    /**
     * 'none' (never had a plan), 'lapsed' (latest term has ended) or 'active';
     * null when there is nobody, or the Plans module is off.
     */
    public function planSituation(): ?string
    {
        if ($this->memberId === null || $this->walkIn || ! $this->organisation()->hasFeature(Feature::Plans)) {
            return null;
        }

        $today = Carbon::today($this->organisation()->timezone);

        $latest = MemberSubscription::query()
            ->where('member_id', $this->memberId)
            ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
            ->orderByDesc('end_date')
            ->first();

        if ($latest === null) {
            return 'none';
        }

        return $latest->end_date->lt($today) ? 'lapsed' : 'active';
    }

    /**
     * Opens the plan dialog set up for the likely case, as the member page
     * does: the latest plan again, from the day after it ended (or today).
     */
    public function preparePlan(): void
    {
        $member = $this->selectedMemberModel();

        if ($member === null) {
            return;
        }

        $this->authorize('createFor', [MemberSubscription::class, $member]);

        $today = Carbon::today($this->organisation()->timezone);
        $latest = $member->latestTerm();

        $this->planId = $latest?->plan_id;
        $this->planStartDate = $latest && $latest->end_date->toDateString() >= $today->toDateString()
            ? $latest->end_date->copy()->addDay()->toDateString()
            : $today->toDateString();

        $this->prefillPlanDiscount();
        $this->resetErrorBag(['planId', 'planStartDate', 'planDiscount']);

        $this->dispatch('open-modal', 'start-plan');
    }

    public function updatedPlanId(): void
    {
        $this->prefillPlanDiscount();
    }

    public function startPlanToday(): void
    {
        $this->planStartDate = Carbon::today($this->organisation()->timezone)->toDateString();
    }

    private function prefillPlanDiscount(): void
    {
        $plan = $this->planId ? Plan::query()->find($this->planId) : null;
        $club = $this->selectedMemberModel()?->primaryClub;

        $this->planDiscount = $plan && $club && $club->discountFor($plan) > 0
            ? $this->major($club->discountFor($plan))
            : '';
    }

    /**
     * Creates the term and makes it the thing being paid, so the amount and
     * discount below already read what this term owes.
     */
    public function startPlan(): void
    {
        $member = $this->selectedMemberModel();

        if ($member === null) {
            return;
        }

        $this->authorize('createFor', [MemberSubscription::class, $member]);

        $organisation = $this->organisation();

        $validated = $this->validate([
            'planId' => ['required', Rule::exists('plans', 'id')->where('organisation_id', $organisation->id)->where('status', PlanStatus::Active->value)],
            'planStartDate' => ['nullable', 'date'],
            'planDiscount' => ['nullable', 'numeric', 'min:0'],
        ], [], ['planId' => 'plan', 'planDiscount' => 'discount']);

        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($validated['planId']);

        $discount = $validated['planDiscount'] !== null && $validated['planDiscount'] !== ''
            ? Money::parseMajor((string) $validated['planDiscount'], $organisation->currency_code)?->minor
            : null;

        if ($discount !== null && $discount > $plan->price_minor) {
            $this->addError('planDiscount', 'The discount cannot be more than the plan price of '.$organisation->money($plan->price_minor).'.');

            return;
        }

        $subscription = app(CreateSubscription::class)->handle(
            member: $member,
            plan: $plan,
            actor: $this->currentMembership(),
            startDate: $validated['planStartDate'] ? Carbon::parse($validated['planStartDate']) : null,
            discountMinor: $discount,
        );

        $this->dispatch('close-modal', 'start-plan');
        $this->selectTarget('plan:'.$subscription->id);

        session()->flash('status', "\"{$plan->name}\" ".($subscription->start_date->isFuture() ? 'renewed' : 'started')." for {$member->name} — collect the fee below.");
    }

    private function selectedMemberModel(): ?Member
    {
        return $this->memberId === null ? null : Member::query()->with('primaryClub')->find($this->memberId);
    }

    /**
     * Cash goes into the cash account, so there is nothing to choose: the
     * account follows the method and the picker only appears for a bank,
     * UPI or card payment (or when no cash account exists to fall back on).
     */
    public function updatedPaymentMethod(): void
    {
        $this->applyMethodAccount();
    }

    private function applyMethodAccount(): void
    {
        $cash = $this->cashAccount();

        if ($this->paymentMethod === PaymentMethod::Cash->value) {
            $this->financialAccountId = $cash !== null ? $cash->id : $this->financialAccountId;

            return;
        }

        // Switching away from cash: the cash account is no longer the answer.
        if ($cash !== null && $this->financialAccountId === $cash->id) {
            $this->financialAccountId = null;
        }
    }

    private function cashAccount(): ?FinancialAccount
    {
        return FinancialAccount::query()
            ->where('status', FinancialAccountStatus::Active)
            ->where('account_type', FinancialAccountType::Cash)
            ->orderBy('id')
            ->first();
    }

    /**
     * Whether the account picker is shown: hidden for a cash payment that
     * has a cash account to land in.
     */
    public function needsAccountChoice(): bool
    {
        return $this->paymentMethod !== PaymentMethod::Cash->value || $this->cashAccount() === null;
    }

    public function clearMember(): void
    {
        $this->reset(['memberId', 'walkIn', 'payerName', 'payerPhone', 'subscriptionId', 'invoiceId', 'target', 'amount', 'discount', 'memberSearch']);

        if (! $this->organisation()->hasFeature(Feature::Members)) {
            $this->walkIn = true;
        }
    }

    /**
     * Switches to collecting from someone who is not a member. Whatever was
     * typed in the search box is the likely name, so it carries over.
     */
    public function startWalkIn(?string $name = null, ?string $phone = null): void
    {
        $this->reset(['memberId', 'subscriptionId', 'invoiceId', 'target', 'amount', 'discount', 'useCredit', 'creditAmount']);

        $this->walkIn = true;
        $this->payerName = trim($name ?? $this->memberSearch);
        $this->payerPhone = trim($phone ?? '');
        $this->memberSearch = '';
    }

    public function save(): void
    {
        $this->authorize('create', FeePayment::class);

        $organisation = $this->organisation();
        $actor = $this->currentMembership();

        $validated = $this->validate([
            'memberId' => [
                Rule::requiredIf(! $this->walkIn),
                'nullable',
                Rule::exists('members', 'id')->where('organisation_id', $organisation->id),
            ],
            'payerName' => [Rule::requiredIf($this->walkIn), 'nullable', 'string', 'max:255'],
            'payerPhone' => ['nullable', 'string', 'max:50'],
            'subscriptionId' => ['nullable', Rule::exists('member_subscriptions', 'id')->where('organisation_id', $organisation->id)],
            'invoiceId' => ['nullable', Rule::exists('invoices', 'id')->where('organisation_id', $organisation->id)],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'useCredit' => ['boolean'],
            'creditAmount' => ['nullable', 'numeric', 'min:0'],
            'paymentMethod' => ['required', Rule::enum(PaymentMethod::class)],
            // Every collection has to name the account the money landed in:
            // without it the account balances on the finance pages are a
            // partial picture, and an admin confirming the payment has no way
            // to check it against a statement.
            'financialAccountId' => [
                'required',
                Rule::exists('financial_accounts', 'id')
                    ->where('organisation_id', $organisation->id)
                    ->where('status', FinancialAccountStatus::Active->value),
            ],
            'transactionReference' => ['nullable', 'string', 'max:255'],
            // "Today" in the organisation's timezone, not the server's: an
            // evening payment in Kolkata is still today there while UTC has
            // not caught up.
            'paymentDate' => ['required', 'date', 'before_or_equal:'.Carbon::today($organisation->timezone)->toDateString()],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'financialAccountId.required' => 'Choose the account this money was received into.',
            'memberId.required' => 'Choose a '.strtolower($organisation->term('member_singular')).', or collect from someone who is not one.',
            'payerName.required' => 'Enter the name of the person paying.',
        ], [
            'memberId' => $organisation->term('member_singular'),
            'payerName' => 'payer name',
            'amount' => 'amount',
            'financialAccountId' => 'receiving account',
        ]);

        /** @var Member|null $member */
        $member = $this->walkIn ? null : Member::query()->findOrFail($validated['memberId']);

        // Club scope is derived from the member, never accepted from the
        // client, and is re-authorized here (MEP.md 4.3). A walk-in payment
        // has no club and no member to scope by.
        $this->authorize('createForClub', [FeePayment::class, $member?->primary_club_id]);

        $payerPhone = null;

        if ($this->walkIn && $validated['payerPhone'] !== null && $validated['payerPhone'] !== '') {
            $payerPhone = PhoneNumber::normalise($validated['payerPhone'], $organisation->defaultCountry());

            if ($payerPhone === null) {
                $this->addError('payerPhone', 'Enter a valid WhatsApp number, or leave it empty.');

                return;
            }
        }

        $money = Money::parseMajor($validated['amount'], $organisation->currency_code);
        $discount = $validated['discount'] !== null && $validated['discount'] !== ''
            ? Money::parseMajor((string) $validated['discount'], $organisation->currency_code)
            : null;
        $discountMinor = $discount->minor ?? 0;

        $creditMinor = 0;

        if ($this->useCredit && $validated['creditAmount'] !== null && $validated['creditAmount'] !== '') {
            $creditMinor = Money::parseMajor((string) $validated['creditAmount'], $organisation->currency_code)->minor ?? 0;
        }

        if ($money === null || $money->minor < 0 || $discountMinor < 0 || $creditMinor < 0) {
            $this->addError('amount', 'Enter a valid amount.');

            return;
        }

        // The amount is what is being paid in total; the credit is the part of
        // it that unlinked money covers, so it can never be more than the amount.
        if ($creditMinor > $money->minor) {
            $this->addError('creditAmount', 'The unlinked money applied cannot be more than the amount being paid.');

            return;
        }

        // Nothing paid and nothing written off is not a payment.
        if ($money->minor + $discountMinor <= 0) {
            $this->addError('amount', 'Enter an amount, a discount, or both.');

            return;
        }

        // Unlinked money can only go towards something, and only as much as
        // the member actually has sitting unlinked.
        if ($creditMinor > 0) {
            if ($this->target === 'other') {
                $this->addError('creditAmount', 'Choose the plan, invoice, or admission fee this money should go towards.');

                return;
            }

            // Only a member has unlinked money to draw on.
            $available = $member?->unlinkedCreditMinor() ?? 0;

            if ($creditMinor > $available) {
                $this->addError('creditAmount', 'Only '.$organisation->money($available).' of unlinked money is available for this '.strtolower($organisation->term('member_singular')).'.');

                return;
            }
        }

        // What actually changes hands now.
        $receivedMinor = $money->minor - $creditMinor;

        $purpose = PaymentPurpose::Other;

        if ($this->target === 'admission') {
            if ($member === null || $member->admissionOutstandingMinor() <= 0) {
                $this->addError('target', 'No admission fee is owed by this '.strtolower($organisation->term('member_singular')).'.');

                return;
            }

            $purpose = PaymentPurpose::Admission;
        }

        // Where a balance is known, money plus discount may not exceed it: an
        // overpayment would leave the balance wrong in the other direction
        // with no way to express a refund.
        $outstanding = $this->targetOutstanding();

        if ($outstanding !== null && $money->minor + $discountMinor > $outstanding) {
            $this->addError($discountMinor > 0 ? 'discount' : 'amount', 'Only '.$organisation->money($outstanding).' is still owed here'
                .($discountMinor > 0 ? ' — the amount and discount together exceed it.' : '.'));

            return;
        }

        if ($discountMinor > 0 && $outstanding === null) {
            $this->addError('discount', 'A discount needs something to come off — choose the plan, invoice, or admission fee it applies to.');

            return;
        }

        // An invoice may only be paid by the member it was raised against, only
        // while it is open, and never for more than is still owed on it: an
        // overpayment would leave the balance wrong in the other direction with
        // no way to express a refund.
        $invoiceId = $validated['invoiceId'];
        $invoice = null;

        if ($invoiceId !== null) {
            $invoice = $this->openInvoicesForMember()->firstWhere('id', $invoiceId);

            if ($invoice === null) {
                $this->addError('target', 'That invoice is not open for this '.strtolower($organisation->term('member_singular')).'.');

                return;
            }

            $purpose = PaymentPurpose::Invoice;
        }

        // A subscription may only be paid by the member it belongs to.
        $subscriptionId = $validated['subscriptionId'];

        if ($subscriptionId !== null && ! $this->subscriptionsForMember()->contains('id', $subscriptionId)) {
            $subscriptionId = null;
        }

        if ($invoice === null && $subscriptionId !== null) {
            $purpose = PaymentPurpose::Plan;
        }

        $result = app(RecordFeePayment::class)->handle(
            attributes: [
                'club_id' => $member?->primary_club_id,
                'member_id' => $member?->id,
                'subscription_id' => $invoice === null ? $subscriptionId : null,
                'invoice_id' => $invoice?->id,
                'purpose' => $purpose->value,
                'payer_name' => $member !== null ? $member->name : (string) $validated['payerName'],
                'payer_phone' => $member === null ? $payerPhone : null,
                'amount_minor' => $receivedMinor,
                'discount_minor' => $discountMinor,
                'credit_applied_minor' => $creditMinor,
                'currency_code' => $organisation->currency_code,
                'payment_method' => $validated['paymentMethod'],
                'financial_account_id' => $validated['financialAccountId'],
                'transaction_reference' => $validated['transactionReference'] ?: null,
                'payment_date' => $validated['paymentDate'],
                'notes' => $validated['notes'] ?: null,
            ],
            actor: $actor,
            confirmImmediately: $this->confirmImmediately,
        );

        session()->flash('status', $result->payment->isConfirmed()
            ? 'Payment confirmed. You can send the receipt from the payment page.'
            : 'Payment submitted and is now awaiting admin confirmation.');

        $this->redirect(route('tenant.finance.payments.show', $result->payment), navigate: true);
    }

    /**
     * Members a fee can be collected from: live ones within the acting
     * user's clubs.
     *
     * @return Builder<Member>
     */
    protected function selectableMembers(): Builder
    {
        return $this->restrictToClubs(Member::query(), 'primary_club_id')
            ->with('primaryClub:id,name')
            ->whereIn('status', [MemberStatus::Active, MemberStatus::Paused]);
    }

    /**
     * @return Collection<int, Member>
     */
    protected function searchableMembers(bool $ignoreSearchLength = false): Collection
    {
        // Remote search requires a minimum length so a keystroke doesn't scan
        // the whole member table (MEP.md 8.4).
        if (! $ignoreSearchLength && mb_strlen($this->memberSearch) < 2) {
            return collect();
        }

        return $this->selectableMembers()
            ->when($this->memberSearch !== '', fn ($query) => $query->where(
                fn ($inner) => $inner->where('name', 'ilike', "%{$this->memberSearch}%")
                    ->orWhere('phone', 'ilike', "%{$this->memberSearch}%")
            ))
            ->when($this->memberId !== null && $this->memberSearch === '', fn ($query) => $query->orWhere('id', $this->memberId))
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    /**
     * @return Collection<int, Invoice>
     */
    protected function openInvoicesForMember(): Collection
    {
        // No Invoices module, nothing to pay an invoice against.
        if (! $this->organisation()->hasFeature(Feature::Billing)) {
            return collect();
        }

        // A walk-in payer can settle the walk-in invoices raised in their name.
        if ($this->walkIn) {
            if (trim($this->payerName) === '') {
                return collect();
            }

            return Invoice::query()
                ->whereNull('member_id')
                ->whereRaw('lower(payer_name) = ?', [mb_strtolower(trim($this->payerName))])
                ->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid])
                ->orderBy('due_date')
                ->orderBy('id')
                ->get();
        }

        if ($this->memberId === null) {
            return collect();
        }

        return Invoice::query()
            ->where('member_id', $this->memberId)
            ->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid])
            ->orderBy('due_date')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, MemberSubscription>
     */
    protected function subscriptionsForMember(): Collection
    {
        if ($this->memberId === null || ! $this->organisation()->hasFeature(Feature::Plans)) {
            return collect();
        }

        return MemberSubscription::query()
            ->with('plan:id,name')
            ->where('member_id', $this->memberId)
            ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
            ->orderByDesc('end_date')
            ->limit(10)
            ->get();
    }

    public function render(): View
    {
        $selectedMember = $this->memberId === null
            ? null
            : Member::query()->with('primaryClub:id,name')->find($this->memberId);

        return view('livewire.finance.payments.form', [
            'organisation' => $this->organisation(),
            'results' => $this->memberId === null ? $this->searchableMembers() : collect(),
            'selectedMember' => $selectedMember,
            'subscriptions' => $this->subscriptionsForMember(),
            'openInvoices' => $this->openInvoicesForMember(),
            'admissionOutstanding' => $selectedMember?->admissionOutstandingMinor() ?? 0,
            'targetOutstanding' => $this->targetOutstanding(),
            'availableCredit' => $selectedMember?->unlinkedCreditMinor() ?? 0,
            'receivedNow' => $this->receivedNowMinor(),
            'planSituation' => $this->planSituation(),
            'canStartPlan' => $selectedMember !== null && (auth()->user()?->can('createFor', [MemberSubscription::class, $selectedMember]) ?? false),
            'availablePlans' => $this->planSituation() !== null && $this->planSituation() !== 'active'
                ? Plan::query()->where('status', PlanStatus::Active)->orderBy('name')->get()
                : collect(),
            'today' => Carbon::today($this->organisation()->timezone),
            'methods' => PaymentMethod::cases(),
            'accounts' => auth()->user()?->can('select', FinancialAccount::class)
                ? FinancialAccount::query()->where('status', FinancialAccountStatus::Active)->orderBy('name')->get()
                : collect(),
            'needsAccountChoice' => $this->needsAccountChoice(),
            'cashAccount' => $this->cashAccount(),
            'isAdmin' => $this->currentMembership()->isAdmin(),
        ])->layout('components.layouts.app', ['heading' => 'Collect fee']);
    }
}
