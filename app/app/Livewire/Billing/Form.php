<?php

declare(strict_types=1);

namespace App\Livewire\Billing;

use App\Actions\Billing\IssueInvoice;
use App\Enums\BillableItemStatus;
use App\Enums\Feature;
use App\Enums\MemberStatus;
use App\Livewire\Concerns\LazyPage;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\BillableItem;
use App\Models\Invoice;
use App\Models\Member;
use App\Support\Money;
use App\Support\PageQuery;
use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use InvalidArgumentException;
use Livewire\Attributes\Defer;
use Livewire\Component;

/**
 * Raising an invoice (MEP.md 6.8): pick the member, build the lines from the
 * price list or by hand, set a due date, issue.
 *
 * Prices on the form are what gets billed. They start from the catalogue but
 * are editable per line, because a discount agreed at the counter is a fact
 * about this invoice, not a reason to change the price list.
 *
 * @phpstan-type LineState array{billable_item_id: int|null, description: string, quantity: int, price: string}
 */
#[Defer]
class Form extends Component
{
    use LazyPage, ResolvesMembership;

    public string $memberSearch = '';

    public ?int $memberId = null;

    /** Billing someone who is not a member: the invoice names them directly. */
    public bool $walkIn = false;

    public string $payerName = '';

    public string $payerPhone = '';

    /** @var array<int, LineState> */
    public array $lines = [];

    public ?int $pickedItemId = null;

    public string $dueDate = '';

    public string $notes = '';

    public function mount(?int $member = null): void
    {
        $this->authorize('create', Invoice::class);

        // Reached from a member's page with ?member=<id> — a query parameter,
        // which Livewire does not pass to mount() on its own.
        $member ??= PageQuery::integer('member');

        $this->dueDate = Carbon::today($this->organisation()->timezone)->addDays(7)->toDateString();

        // Without the Members module every invoice is made out by name.
        if (! $this->organisation()->hasFeature(Feature::Members)) {
            $this->walkIn = true;
        } elseif ($member !== null) {
            $this->selectMember($member);
        }
    }

    public function selectMember(int $memberId): void
    {
        if (! $this->organisation()->hasFeature(Feature::Members)) {
            return;
        }

        // Looked up directly, not through the search list, which is capped
        // and would miss a member further down the alphabet.
        $member = $this->selectableMembers()->find($memberId);

        if (! $member) {
            return;
        }

        $this->memberId = $member->id;
        $this->memberSearch = '';
    }

    public function clearMember(): void
    {
        $this->reset(['memberId', 'memberSearch', 'walkIn', 'payerName', 'payerPhone']);

        if (! $this->organisation()->hasFeature(Feature::Members)) {
            $this->walkIn = true;
        }
    }

    /**
     * Switches to billing someone who is not a member. Whatever was typed in
     * the search box is the likely name, so it carries over.
     */
    public function startWalkIn(): void
    {
        $this->reset(['memberId']);

        $this->walkIn = true;
        $this->payerName = trim($this->memberSearch);
        $this->memberSearch = '';
    }

    /**
     * Choosing an item in the picker is the whole gesture: the line is added
     * and the picker resets, ready for the next one.
     */
    public function updatedPickedItemId(): void
    {
        $this->addItem();
    }

    /**
     * Adds a line from the price list, pre-filled with its current price. The
     * same item picked twice bumps the quantity rather than repeating a row.
     */
    public function addItem(): void
    {
        if ($this->pickedItemId === null || $this->pickedItemId === 0) {
            $this->pickedItemId = null;

            return;
        }

        $item = $this->catalogue()->firstWhere('id', $this->pickedItemId);

        if (! $item) {
            return;
        }

        foreach ($this->lines as $index => $line) {
            if ($line['billable_item_id'] === $item->id) {
                $this->lines[$index]['quantity']++;
                $this->pickedItemId = null;

                return;
            }
        }

        $this->lines[] = [
            'billable_item_id' => $item->id,
            'description' => $item->name,
            'quantity' => 1,
            'price' => (string) Money::ofMinor($item->unit_price_minor, $this->organisation()->currency_code)->major(),
        ];

        $this->pickedItemId = null;
    }

    /**
     * A blank line for something not on the price list. Kept deliberately
     * possible: a one-off charge should not require an admin to edit settings
     * first.
     */
    public function addCustomLine(): void
    {
        $this->lines[] = [
            'billable_item_id' => null,
            'description' => '',
            'quantity' => 1,
            'price' => '',
        ];
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);

        $this->lines = array_values($this->lines);
    }

    public function issue(): void
    {
        $this->authorize('create', Invoice::class);

        $organisation = $this->organisation();

        $this->validate([
            'memberId' => [Rule::requiredIf(! $this->walkIn), 'nullable', Rule::exists('members', 'id')->where('organisation_id', $organisation->id)],
            'payerName' => [Rule::requiredIf($this->walkIn), 'nullable', 'string', 'max:255'],
            'payerPhone' => ['nullable', 'string', 'max:50'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.description' => ['required', 'string', 'max:160'],
            'lines.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'lines.*.price' => ['required', 'numeric', 'min:0'],
            'dueDate' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'lines.required' => 'Add at least one line before issuing.',
            'lines.*.description.required' => 'Every line needs a description.',
            'lines.*.price.required' => 'Every line needs a price.',
            'memberId.required' => 'Choose a '.strtolower($organisation->term('member_singular')).', or bill someone who is not one.',
            'payerName.required' => 'Enter the name this invoice is for.',
        ], [
            'memberId' => $organisation->term('member_singular'),
            'payerName' => 'name',
            'lines.*.description' => 'description',
            'lines.*.quantity' => 'quantity',
            'lines.*.price' => 'price',
        ]);

        /** @var Member|null $member */
        $member = $this->walkIn ? null : Member::query()->findOrFail($this->memberId);

        // Club scope comes from the member, never from the client; a walk-in
        // invoice has neither.
        $this->authorize('createForClub', [Invoice::class, $member?->primary_club_id]);

        $payerPhone = null;

        if ($this->walkIn && trim($this->payerPhone) !== '') {
            $payerPhone = PhoneNumber::normalise($this->payerPhone, $organisation->defaultCountry());

            if ($payerPhone === null) {
                $this->addError('payerPhone', 'Enter a valid WhatsApp number, or leave it empty.');

                return;
            }
        }

        $lines = [];

        foreach ($this->lines as $index => $line) {
            $money = Money::parseMajor((string) $line['price'], $organisation->currency_code);

            if ($money === null) {
                $this->addError('lines.'.$index.'.price', 'Enter a valid price.');

                return;
            }

            $lines[] = [
                'billable_item_id' => $line['billable_item_id'],
                'description' => $line['description'],
                'quantity' => (int) $line['quantity'],
                'unit_price_minor' => $money->minor,
            ];
        }

        try {
            $issued = app(IssueInvoice::class)->handle(
                member: $member,
                lines: $lines,
                actor: $this->currentMembership(),
                dueDate: $this->dueDate !== '' ? Carbon::parse($this->dueDate) : null,
                notes: $this->notes ?: null,
                payerName: $this->walkIn ? $this->payerName : null,
                payerPhone: $payerPhone,
            );
        } catch (InvalidArgumentException $exception) {
            $this->addError('lines', $exception->getMessage());

            return;
        }

        session()->flash('status', 'Invoice '.$issued->invoice->number.' issued for '.$issued->invoice->billedToName().'.');
        session()->flash('notification_id', $issued->notification?->id);

        $this->redirect(route('tenant.billing.show', $issued->invoice), navigate: true);
    }

    public function totalMinor(): int
    {
        $currency = $this->organisation()->currency_code;
        $total = 0;

        foreach ($this->lines as $line) {
            $money = Money::parseMajor((string) $line['price'], $currency);
            $total += ($money === null ? 0 : $money->minor) * max(1, (int) $line['quantity']);
        }

        return $total;
    }

    /**
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
     * @return Collection<int, BillableItem>
     */
    protected function catalogue(): Collection
    {
        return BillableItem::query()
            ->where('status', BillableItemStatus::Active)
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        $selectedMember = $this->memberId === null
            ? null
            : Member::query()->with('primaryClub:id,name')->find($this->memberId);

        return view('livewire.billing.form', [
            'organisation' => $this->organisation(),
            'results' => $this->memberId === null ? $this->searchableMembers() : collect(),
            'selectedMember' => $selectedMember,
            'catalogue' => $this->catalogue(),
            'total' => $this->totalMinor(),
        ])->layout('components.layouts.app', ['heading' => 'New invoice']);
    }
}
