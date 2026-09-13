<?php

declare(strict_types=1);

namespace App\Enums;

enum ExpenseStatus: string
{
    case Completed = 'completed';
    case Reversed = 'reversed';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Completed',
            self::Reversed => 'Reversed',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Completed => 'positive',
            self::Reversed => 'neutral',
        };
    }
}
