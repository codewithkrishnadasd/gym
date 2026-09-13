<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Models\FeePayment;
use App\Models\WhatsappActionNotification;

/**
 * The completed confirmation plus the notification the admin may act on. The
 * WhatsApp deep link is never opened here — that is always an explicit
 * client-side admin gesture afterwards (MEP.md 5.11.1).
 */
final readonly class ConfirmationResult
{
    public function __construct(
        public FeePayment $payment,
        public ?WhatsappActionNotification $notification,
    ) {}
}
