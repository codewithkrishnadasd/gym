<?php

declare(strict_types=1);

namespace App\Enums;

enum StorageBucketStatus: string
{
    case Active = 'active';

    /** Stored as "archived" so existing rows stay valid; shown as "Removed". */
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Archived => 'Removed',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Active => 'positive',
            self::Archived => 'neutral',
        };
    }
}
