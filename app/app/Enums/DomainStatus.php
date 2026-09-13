<?php

declare(strict_types=1);

namespace App\Enums;

enum DomainStatus: string
{
    case Active = 'active';
    case Pending = 'pending';
    case Disabled = 'disabled';
    case Reserved = 'reserved';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Pending => 'Pending',
            self::Disabled => 'Disabled',
            self::Reserved => 'Reserved',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Active => 'positive',
            self::Pending => 'caution',
            self::Disabled => 'critical',
            self::Reserved => 'neutral',
        };
    }
}
