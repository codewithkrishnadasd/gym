<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case BankTransfer = 'bank_transfer';
    case Upi = 'upi';
    case Card = 'card';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::BankTransfer => 'Bank transfer',
            self::Upi => 'UPI',
            self::Card => 'Card',
            self::Other => 'Other',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Cash => 'neutral',
            self::BankTransfer => 'neutral',
            self::Upi => 'neutral',
            self::Card => 'neutral',
            self::Other => 'neutral',
        };
    }
}
