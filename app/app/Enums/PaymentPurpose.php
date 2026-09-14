<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * What a fee payment settles. Decides which balance a confirmation credits
 * and how the payment is described on receipts and in lists.
 */
enum PaymentPurpose: string
{
    case Plan = 'plan';
    case Invoice = 'invoice';
    case Admission = 'admission';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Plan => 'Plan fee',
            self::Invoice => 'Invoice',
            self::Admission => 'Admission fee',
            self::Other => 'Other',
        };
    }
}
