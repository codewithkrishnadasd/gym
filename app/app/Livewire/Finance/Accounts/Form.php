<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Accounts;

use App\Enums\FinancialAccountStatus;
use App\Enums\FinancialAccountType;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\FinancialAccount;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Bank/UPI/cash account setup (MEP.md 6.9).
 *
 * Only the last four digits of an account number are ever stored, and the QR
 * payload is validated before saving — no full credentials or secrets go into
 * the database (MEP.md 5.10).
 */
class Form extends Component
{
    use ResolvesMembership;

    public ?FinancialAccount $account = null;

    public string $name = '';

    public string $accountType = 'bank';

    public string $bankName = '';

    public string $accountNumberLast4 = '';

    public string $upiId = '';

    public string $qrPayload = '';

    public string $status = 'active';

    public function mount(?FinancialAccount $financialAccount = null): void
    {
        $this->authorize($financialAccount ? 'update' : 'create', FinancialAccount::class);

        $this->account = $financialAccount;

        if ($financialAccount) {
            $this->name = $financialAccount->name;
            $this->accountType = $financialAccount->account_type->value;
            $this->bankName = (string) $financialAccount->bank_name;
            $this->accountNumberLast4 = (string) $financialAccount->account_number_last4;
            $this->upiId = (string) $financialAccount->upi_id;
            $this->qrPayload = (string) $financialAccount->qr_payload;
            $this->status = $financialAccount->status->value;
        }
    }

    public function save(): void
    {
        $this->authorize($this->account ? 'update' : 'create', FinancialAccount::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'accountType' => ['required', Rule::enum(FinancialAccountType::class)],
            'bankName' => ['nullable', 'string', 'max:255'],
            // Deliberately capped at four digits: full account numbers are
            // never stored (MEP.md 5.10).
            'accountNumberLast4' => ['nullable', 'digits:4'],
            'upiId' => ['nullable', 'string', 'max:255', 'regex:/^[\w.\-]{2,64}@[a-zA-Z]{2,32}$/'],
            'qrPayload' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::enum(FinancialAccountStatus::class)],
        ], [
            'upiId.regex' => 'Enter a valid UPI ID, for example name@bank.',
            'accountNumberLast4.digits' => 'Enter only the last 4 digits of the account number.',
        ]);

        if ($validated['accountType'] === FinancialAccountType::Upi->value && $validated['upiId'] === '') {
            $this->addError('upiId', 'A UPI account needs a UPI ID.');

            return;
        }

        $attributes = [
            'name' => $validated['name'],
            'account_type' => $validated['accountType'],
            'bank_name' => $validated['bankName'] ?: null,
            'account_number_last4' => $validated['accountNumberLast4'] ?: null,
            'upi_id' => $validated['upiId'] ?: null,
            'qr_payload' => $validated['qrPayload'] ?: null,
            'status' => $validated['status'],
        ];

        $this->account ? $this->account->update($attributes) : FinancialAccount::create($attributes);

        session()->flash('status', "\"{$validated['name']}\" was saved.");

        $this->redirect(route('tenant.finance.accounts.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.finance.accounts.form', [
            'types' => FinancialAccountType::cases(),
            'statuses' => FinancialAccountStatus::cases(),
        ])->layout('components.layouts.app', [
            'heading' => $this->account ? "Edit {$this->account->name}" : 'New account',
        ]);
    }
}
