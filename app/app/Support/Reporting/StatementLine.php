<?php

declare(strict_types=1);

namespace App\Support\Reporting;

use Carbon\CarbonInterface;

/**
 * A single money movement on a financial account statement (MEP.md 6.9).
 * Confirmed payments are inbound; completed expenses are outbound.
 */
final readonly class StatementLine
{
    public function __construct(
        public CarbonInterface $date,
        public string $description,
        public bool $inbound,
        public int $amountMinor,
    ) {}

    public function signedAmount(): int
    {
        return $this->inbound ? $this->amountMinor : -$this->amountMinor;
    }
}
