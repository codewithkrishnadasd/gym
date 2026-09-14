<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Expenses;

use App\Enums\ExpenseTargetType;
use App\Enums\FinancialAccountStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Club;
use App\Models\Expense;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\OrganisationUser;
use App\Support\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 * Records an organisation, club, member, or user-related expense
 * (MEP.md 6.10). Receipts go to the configured filesystem disk (S3-compatible
 * object storage in production) rather than the container filesystem, so any
 * app replica can serve them (MEP.md 8.5).
 */
class Form extends Component
{
    use ResolvesMembership, WithFileUploads;

    public ?Expense $expense = null;

    public string $category = '';

    public string $amount = '';

    public string $expenseDate = '';

    public ?int $clubId = null;

    public ?int $fundingAccountId = null;

    public string $payee = '';

    public string $description = '';

    public string $targetType = '';

    public ?int $targetId = null;

    public ?TemporaryUploadedFile $receipt = null;

    public function mount(?Expense $expense = null): void
    {
        $this->authorize($expense ? 'update' : 'create', $expense ?? Expense::class);

        $this->expense = $expense;
        $this->expenseDate = Carbon::today($this->organisation()->timezone)->toDateString();

        if ($expense) {
            $this->category = $expense->category;
            $this->amount = (string) Money::ofMinor($expense->amount_minor, $expense->currency_code)->major();
            $this->expenseDate = $expense->expense_date->toDateString();
            $this->clubId = $expense->club_id;
            $this->fundingAccountId = $expense->paid_from_financial_account_id;
            $this->payee = (string) $expense->payee;
            $this->description = (string) $expense->description;
            $this->targetType = $expense->target_type->value ?? '';
            $this->targetId = $expense->target_id;
        }
    }

    public function updatedTargetType(): void
    {
        $this->targetId = null;
    }

    public function save(): void
    {
        $this->authorize($this->expense ? 'update' : 'create', $this->expense ?? Expense::class);

        $organisation = $this->organisation();

        // A deactivated category is still recognised everywhere old expenses
        // are read, so it has to be rejected here explicitly — the picker
        // hides it, but the field accepts typed values.
        $retired = array_diff($organisation->allExpenseCategories(), $organisation->expenseCategories());

        $validated = $this->validate([
            'category' => [
                'required', 'string', 'max:100',
                Rule::notIn($retired),
            ],
            'amount' => ['required', 'numeric', 'gt:0'],
            'expenseDate' => ['required', 'date', 'before_or_equal:'.Carbon::today($this->organisation()->timezone)->toDateString()],
            'clubId' => ['nullable', Rule::exists('clubs', 'id')->where('organisation_id', $organisation->id)],
            'fundingAccountId' => ['nullable', Rule::exists('financial_accounts', 'id')->where('organisation_id', $organisation->id)],
            'payee' => ['nullable', 'string', 'max:255'],
            // MEP.md 10: an expense needs a description or a payee.
            'description' => ['required_without:payee', 'nullable', 'string', 'max:1000'],
            'targetType' => ['nullable', Rule::enum(ExpenseTargetType::class)],
            'targetId' => ['nullable', 'integer'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'description.required_without' => 'Add a description or a payee so this expense can be identified later.',
            'category.not_in' => 'That category is no longer in use. Pick another, or ask an admin to reactivate it in Settings.',
        ]);

        $money = Money::parseMajor($validated['amount'], $organisation->currency_code);

        if ($money === null || $money->minor <= 0) {
            $this->addError('amount', 'Enter a valid amount greater than zero.');

            return;
        }

        $targetType = $validated['targetType'] ?: null;
        $targetId = $this->resolveTargetId($targetType);

        $receiptPath = $this->expense?->receipt_path;

        if ($this->receipt) {
            $receiptPath = $this->receipt->store('receipts/'.$organisation->id, config('filesystems.default'));
        }

        $attributes = [
            'category' => $validated['category'],
            'amount_minor' => $money->minor,
            'currency_code' => $organisation->currency_code,
            'expense_date' => $validated['expenseDate'],
            'club_id' => $validated['clubId'],
            'paid_from_financial_account_id' => $validated['fundingAccountId'],
            'payee' => $validated['payee'] ?: null,
            'description' => $validated['description'] ?: null,
            'receipt_path' => $receiptPath ?: null,
            'target_type' => $targetType,
            'target_id' => $targetId,
        ];

        $actor = $this->currentMembership();

        DB::transaction(function () use ($attributes, $actor): void {
            if ($this->expense) {
                $before = $this->expense->only(array_keys($attributes));
                $this->expense->update($attributes);
                AuditEvent::record($this->expense, 'expense.updated', $actor, $before, $attributes);

                return;
            }

            $expense = Expense::create([...$attributes, 'created_by' => $actor->id]);
            AuditEvent::record($expense, 'expense.recorded', $actor, null, $attributes);
        });

        session()->flash('status', 'Expense saved.');

        $this->redirect(route('tenant.finance.expenses.index'), navigate: true);
    }

    /**
     * The target must exist inside the resolved tenant; an unmatched ID is
     * dropped rather than stored as a dangling reference.
     */
    private function resolveTargetId(?string $targetType): ?int
    {
        if ($targetType === null || $this->targetId === null) {
            return null;
        }

        $exists = match ($targetType) {
            ExpenseTargetType::Club->value => Club::query()->whereKey($this->targetId)->exists(),
            ExpenseTargetType::Member->value => Member::query()->whereKey($this->targetId)->exists(),
            ExpenseTargetType::User->value => OrganisationUser::query()->whereKey($this->targetId)->exists(),
            // An organisation-wide expense has no specific target row.
            default => false,
        };

        return $exists ? $this->targetId : null;
    }

    public function removeReceipt(): void
    {
        $this->authorize('update', $this->expense ?? Expense::class);

        if ($this->expense?->receipt_path) {
            Storage::disk(config('filesystems.default'))->delete($this->expense->receipt_path);
            $this->expense->update(['receipt_path' => null]);
        }
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.finance.expenses.form', [
            'organisation' => $organisation,
            'clubs' => $this->accessibleClubs(true),
            'accounts' => FinancialAccount::query()->where('status', FinancialAccountStatus::Active)->orderBy('name')->get(),
            'categories' => Expense::categoriesForEntry($organisation),
            'targetTypes' => ExpenseTargetType::cases(),
            'members' => $this->targetType === ExpenseTargetType::Member->value
                ? Member::query()->orderBy('name')->limit(200)->get()
                : collect(),
            'staff' => $this->targetType === ExpenseTargetType::User->value
                ? OrganisationUser::query()->with('user:id,name')->get()
                : collect(),
        ])->layout('components.layouts.app', [
            'heading' => $this->expense ? 'Edit expense' : 'Record expense',
        ]);
    }
}
