<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BillableItemStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\BillableItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One entry in an organisation's price list — a personal-training session, a
 * locker, a towel, a late fee. Chosen when building an invoice so the price
 * and wording are consistent; the invoice line then keeps its own copy, so
 * repricing an item never rewrites a document already sent.
 */
#[Fillable(['organisation_id', 'name', 'description', 'unit_price_minor', 'status', 'created_by'])]
class BillableItem extends Model
{
    /** @use HasFactory<BillableItemFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'status' => BillableItemStatus::class,
            'unit_price_minor' => 'integer',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === BillableItemStatus::Active;
    }
}
