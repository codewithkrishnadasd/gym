<?php

declare(strict_types=1);

namespace App\Support;

/**
 * One row of an attendance roster. Members and staff are marked through the
 * same screen, so both are normalised to this shape before rendering
 * (MEP.md 6.7).
 */
final readonly class RosterEntry
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $detail,
    ) {}
}
