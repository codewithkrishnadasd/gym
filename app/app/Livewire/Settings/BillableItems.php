<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Enums\BillableItemStatus;
use App\Livewire\Concerns\ActsForOrganisation;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\BillableItem;
use App\Support\Money;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * The organisation's price list: everything that can appear as a line on an
 * invoice (MEP.md 6.8).
 *
 * Items are removed, never deleted. An invoice line keeps its own copy of the
 * description and price, so removing an item only stops it being offered on
 * new invoices — nothing already issued changes.
 */
class BillableItems extends Component
{
    use ActsForOrganisation, ResolvesMembership;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $price = '';

    public function mount(): void
    {
        $this->authorize('manage', BillableItem::class);
    }

    public function startCreate(): void
    {
        $this->authorize('manage', BillableItem::class);

        $this->resetForm();
        $this->dispatch('open-modal', 'billable-item');
    }

    public function startEdit(int $itemId): void
    {
        $this->authorize('manage', BillableItem::class);

        $item = BillableItem::query()->findOrFail($itemId);

        $this->resetForm();
        $this->editingId = $item->id;
        $this->name = $item->name;
        $this->description = (string) $item->description;
        $this->price = (string) Money::ofMinor($item->unit_price_minor, $this->organisation()->currency_code)->major();

        $this->dispatch('open-modal', 'billable-item');
    }

    public function save(): void
    {
        $this->authorize('manage', BillableItem::class);

        $organisation = $this->organisation();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $money = Money::parseMajor((string) $validated['price'], $organisation->currency_code);

        if ($money === null) {
            $this->addError('price', 'Enter a valid price.');

            return;
        }

        $item = $this->editingId === null
            ? new BillableItem(['created_by' => $this->actingMembership()?->id, 'status' => BillableItemStatus::Active])
            : BillableItem::query()->findOrFail($this->editingId);

        $before = $item->exists ? $item->only(['name', 'description', 'unit_price_minor']) : null;

        $item->fill([
            'name' => $validated['name'],
            'description' => $validated['description'] ?: null,
            'unit_price_minor' => $money->minor,
        ])->save();

        AuditEvent::record(
            $item,
            $before === null ? 'billable_item.created' : 'billable_item.updated',
            $this->actingMembership(),
            $before,
            $item->only(['name', 'description', 'unit_price_minor']),
        );

        $this->dispatch('close-modal');
        $this->resetForm();

        session()->flash('status', 'Price list saved.');
    }

    public function remove(int $itemId): void
    {
        $this->authorize('manage', BillableItem::class);

        $item = BillableItem::query()->findOrFail($itemId);
        $item->update(['status' => BillableItemStatus::Archived]);

        session()->flash('status', '"'.$item->name.'" was removed. Invoices already issued are unchanged.');
    }

    public function restore(int $itemId): void
    {
        $this->authorize('manage', BillableItem::class);

        BillableItem::query()->findOrFail($itemId)->update(['status' => BillableItemStatus::Active]);
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'description', 'price']);
        $this->resetErrorBag();
    }

    /**
     * @return Collection<int, BillableItem>
     */
    protected function items(): Collection
    {
        return BillableItem::query()->orderBy('status')->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('livewire.settings.billable-items', [
            'organisation' => $this->organisation(),
            'items' => $this->items(),
        ]);
    }
}
