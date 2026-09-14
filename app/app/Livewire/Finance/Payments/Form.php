<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Payments;

use App\Actions\Payments\RecordFeePayment;
use App\Enums\FinancialAccountStatus;
use App\Enums\InvoiceStatus;
use App\Enums\MemberStatus;
use App\Enums\PaymentMethod;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Support\Money;
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

    public ?int $subscriptionId = null;

    /** An invoice this payment settles, in full or in part. */
    public ?int $invoiceId = null;

    public string $amount = '';

    public string $paymentMethod = 'cash';

    public ?int $financialAccountId = null;

    public string $transactionReference = '';

    public string $paymentDate = '';

    public string $notes = '';

    public bool $confirmImmediately = false;

    public function mount(?int $member = null): void
    {
        $this->authorize('create', FeePayment::class);

        // "Collect fee" on a member's page links here with ?member=<id>. That
        // is a query parameter, not a route segment, so Livewire does not pass
        // it to mount() and the picker opened empty.
        $member ??= request()->integer('member') ?: null;

        $this->paymentDate = Carbon::today($this->organisation()->timezone)->toDateString();
        $this->confirmImmediately = $this->currentMembership()->isAdmin();

        if ($member !== null) {
            $this->selectMember($member);
        }

        $invoice = request()->integer('invoice') ?: null;

        if ($invoice !== null) {
            $this->selectInvoice($invoice);
        }
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
        $this->amount = (string) Money::ofMinor($invoice->outstandingMinor(), $this->organisation()->currency_code)->major();
    }

    public function updatedInvoiceId(mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->invoiceId = null;

            return;
        }

        $this->selectInvoice((int) $value);
    }

    public function updatedSubscriptionId(mixed $value): void
    {
        // A plan and an invoice are two different things to pay for.
        if ($value !== null && $value !== '') {
            $this->invoiceId = null;
        }
    }

    public function selectMember(int $memberId): void
    {
        $member = $this->searchableMembers(true)->firstWhere('id', $memberId);

        if (! $member) {
            return;
        }

        $this->memberId = $member->id;
        $this->memberSearch = '';

        $this->invoiceId = null;

        $subscription = $this->subscriptionsForMember()->first();
        $this->subscriptionId = $subscription?->id;

        // Default the amount to whatever is still outstanding on the term.
        if ($subscription) {
            $outstanding = max(0, $subscription->amount_due_minor - $subscription->amount_paid_minor);
            $this->amount = (string) Money::ofMinor($outstanding, $this->organisation()->currency_code)->major();
        }
    }

    public function clearMember(): void
    {
        $this->reset(['memberId', 'subscriptionId', 'invoiceId', 'amount', 'memberSearch']);
    }

    public function save(): void
    {
        $this->authorize('create', FeePayment::class);

        $organisation = $this->organisation();
        $actor = $this->currentMembership();

        $validated = $this->validate([
            'memberId' => ['required', Rule::exists('members', 'id')->where('organisation_id', $organisation->id)],
            'subscriptionId' => ['nullable', Rule::exists('member_subscriptions', 'id')->where('organisation_id', $organisation->id)],
            'invoiceId' => ['nullable', Rule::exists('invoices', 'id')->where('organisation_id', $organisation->id)],
            'amount' => ['required', 'numeric', 'gt:0'],
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
            'paymentDate' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'financialAccountId.required' => 'Choose the account this money was received into.',
        ], [
            'memberId' => $organisation->term('member_singular'),
            'amount' => 'amount',
            'financialAccountId' => 'receiving account',
        ]);

        /** @var Member $member */
        $member = Member::query()->findOrFail($validated['memberId']);

        // Club scope is derived from the member, never accepted from the
        // client, and is re-authorized here (MEP.md 4.3).
        $this->authorize('createForClub', [FeePayment::class, $member->primary_club_id]);

        if ($member->primary_club_id === null) {
            $this->addError('memberId', 'This '.strtolower($organisation->term('member_singular')).' has no club and cannot be billed.');

            return;
        }

        $money = Money::parseMajor($validated['amount'], $organisation->currency_code);

        if ($money === null || $money->minor <= 0) {
            $this->addError('amount', 'Enter a valid amount greater than zero.');

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
                $this->addError('invoiceId', 'That invoice is not open for this '.strtolower($organisation->term('member_singular')).'.');

                return;
            }

            if ($money->minor > $invoice->outstandingMinor()) {
                $this->addError('amount', 'Only '.$organisation->money($invoice->outstandingMinor()).' is still owed on '.$invoice->number.'.');

                return;
            }
        }

        // A subscription may only be paid by the member it belongs to.
        $subscriptionId = $validated['subscriptionId'];

        if ($subscriptionId !== null && ! $this->subscriptionsForMember()->contains('id', $subscriptionId)) {
            $subscriptionId = null;
        }

        $result = app(RecordFeePayment::class)->handle(
            attributes: [
                'club_id' => $member->primary_club_id,
                'member_id' => $member->id,
                'subscription_id' => $invoice === null ? $subscriptionId : null,
                'invoice_id' => $invoice?->id,
                'payer_name' => $member->name,
                'amount_minor' => $money->minor,
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
     * @return Collection<int, Member>
     */
    protected function searchableMembers(bool $ignoreSearchLength = false): Collection
    {
        // Remote search requires a minimum length so a keystroke doesn't scan
        // the whole member table (MEP.md 8.4).
        if (! $ignoreSearchLength && mb_strlen($this->memberSearch) < 2) {
            return collect();
        }

        return Member::query()
            ->with('primaryClub:id,name')
            ->whereIn('primary_club_id', $this->accessibleClubIds())
            ->whereIn('status', [MemberStatus::Active, MemberStatus::Paused])
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
        if ($this->memberId === null) {
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
            'methods' => PaymentMethod::cases(),
            'accounts' => auth()->user()?->can('select', FinancialAccount::class)
                ? FinancialAccount::query()->where('status', FinancialAccountStatus::Active)->orderBy('name')->get()
                : collect(),
            'isAdmin' => $this->currentMembership()->isAdmin(),
        ])->layout('components.layouts.app', ['heading' => 'Collect fee']);
    }
}
