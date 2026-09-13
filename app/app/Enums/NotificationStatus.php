<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationStatus: string
{
    case Ready = 'ready';
    case Opened = 'opened';
    case Skipped = 'skipped';
    case Unavailable = 'unavailable';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Ready => 'Ready to send',
            self::Opened => 'WhatsApp opened',
            self::Skipped => 'Skipped',
            self::Unavailable => 'No usable number',
            self::Failed => 'Failed',
        };
    }

    /**
     * Semantic badge tone from the design system, so one status never renders
     * two different colours in two different views.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Ready => 'info',
            self::Opened => 'positive',
            self::Skipped => 'neutral',
            self::Unavailable => 'caution',
            self::Failed => 'critical',
        };
    }
}
