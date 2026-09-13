<?php

declare(strict_types=1);

namespace App\Enums;

enum FinancialAccountType: string
{
    case Bank = 'bank';
    case Upi = 'upi';
    case Cash = 'cash';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Bank => 'Bank account',
            self::Upi => 'UPI',
            self::Cash => 'Cash',
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
            self::Bank => 'info',
            self::Upi => 'accent',
            self::Cash => 'neutral',
            self::Other => 'neutral',
        };
    }
}
