<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Accounts;

use App\Enums\ConfirmationStatus;
use App\Enums\ExpenseStatus;
use App\Enums\FinancialAccountStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Support\Reporting\StatementLine;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * The focused account view: statement plus a scannable QR code (MEP.md 6.9).
 *
 * The QR is rendered server-side as inline SVG on each request rather than
 * stored as a file — it is cheap to generate, always matches the current
 * payload, and needs no object-storage round trip to display.
 */
class Show extends Component
{
    use ResolvesMembership;

    public FinancialAccount $account;

    public function mount(FinancialAccount $financialAccount): void
    {
        $this->authorize('viewAny', FinancialAccount::class);

        $this->account = $financialAccount;
    }

    /**
     * A QR is shown only for an active account with a payload (MEP.md 5.10).
     */
    protected function qrSvg(): ?string
    {
        if ($this->account->status !== FinancialAccountStatus::Active || ! $this->account->qr_payload) {
            return null;
        }

        // The QR facade returns a framework-agnostic HtmlString shim that
        // static analysis cannot resolve; it is always stringable.
        /** @var string|\Stringable|null $svg */
        $svg = QrCode::format('svg')->size(220)->margin(1)->generate($this->account->qr_payload);

        return $svg === null ? null : (string) $svg;
    }

    /**
     * @return Collection<int, StatementLine>
     */
    protected function statement(): Collection
    {
        $payments = FeePayment::query()
            ->with(['member:id,name'])
            ->where('financial_account_id', $this->account->id)
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->orderByDesc('payment_date')
            ->limit(50)
            ->get()
            ->map(function (FeePayment $payment): StatementLine {
                return new StatementLine(
                    date: $payment->payment_date,
                    description: 'Fee from '.$payment->payerName(),
                    inbound: true,
                    amountMinor: $payment->amount_minor,
                );
            });

        $expenses = Expense::query()
            ->where('paid_from_financial_account_id', $this->account->id)
            ->where('status', ExpenseStatus::Completed)
            ->orderByDesc('expense_date')
            ->limit(50)
            ->get()
            ->map(fn (Expense $expense): StatementLine => new StatementLine(
                date: $expense->expense_date,
                description: $expense->category.($expense->payee ? ' — '.$expense->payee : ''),
                inbound: false,
                amountMinor: $expense->amount_minor,
            ));

        return $payments->concat($expenses)
            ->sortByDesc(fn (StatementLine $line): int => $line->date->getTimestamp())
            ->take(50)
            ->values();
    }

    public function render(): View
    {
        $statement = $this->statement();

        return view('livewire.finance.accounts.show', [
            'organisation' => $this->organisation(),
            'qrSvg' => $this->qrSvg(),
            'statement' => $statement,
            'totalIn' => (int) $statement->where('inbound', true)->sum('amountMinor'),
            'totalOut' => (int) $statement->where('inbound', false)->sum('amountMinor'),
        ])->layout('components.layouts.app', ['heading' => $this->account->name]);
    }
}
