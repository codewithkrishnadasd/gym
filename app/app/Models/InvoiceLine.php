<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One priced line on an invoice. Not tenant-scoped itself: it is only ever
 * reached through its invoice, which is.
 */
#[Fillable(['invoice_id', 'billable_item_id', 'description', 'quantity', 'unit_price_minor', 'line_total_minor', 'position'])]
class InvoiceLine extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price_minor' => 'integer',
            'line_total_minor' => 'integer',
            'position' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * @return BelongsTo<BillableItem, $this>
     */
    public function billableItem(): BelongsTo
    {
        return $this->belongsTo(BillableItem::class);
    }
}
