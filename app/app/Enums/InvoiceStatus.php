<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Where an invoice stands. Derived from `paid_minor` against `total_minor`
 * every time a payment is confirmed or reversed, and stored so lists and
 * reports can filter on it without doing the arithmetic per row.
 *
 * There is no draft: an invoice exists once it is issued. Corrections are made
 * by voiding and reissuing, never by editing lines after the fact.
 */
enum InvoiceStatus: string
{
    case Issued = 'issued';
    case PartiallyPaid = 'partially_paid';
    case Paid = 'paid';
    case Void = 'void';

    public function label(): string
    {
        return match ($this) {
            self::Issued => 'Unpaid',
            self::PartiallyPaid => 'Partly paid',
            self::Paid => 'Paid',
            self::Void => 'Void',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Issued => 'caution',
            self::PartiallyPaid => 'info',
            self::Paid => 'positive',
            self::Void => 'neutral',
        };
    }

    /**
     * Whether money can still be collected against it.
     */
    public function isOpen(): bool
    {
        return in_array($this, [self::Issued, self::PartiallyPaid], true);
    }

    public static function forBalance(int $paidMinor, int $totalMinor): self
    {
        return match (true) {
            $paidMinor <= 0 => self::Issued,
            $paidMinor < $totalMinor => self::PartiallyPaid,
            default => self::Paid,
        };
    }
}
