<?php

declare(strict_types=1);

namespace App\Actions\Billing;

use App\Models\Invoice;
use App\Models\WhatsappActionNotification;

final class IssuedInvoice
{
    public function __construct(
        public readonly Invoice $invoice,
        public readonly ?WhatsappActionNotification $notification,
    ) {}
}
