<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when a record is asked to make a transition its lifecycle does not
 * permit — for example confirming a payment that was already rejected. These
 * are guarded at the database read inside the transaction, so they signal a
 * stale UI rather than a programming error, and are surfaced to the operator
 * as a recoverable message.
 */
final class LifecycleViolation extends RuntimeException
{
    public static function payment(string $from, string $to): self
    {
        return new self("This payment is already {$from} and cannot be {$to}.");
    }

    public static function expense(string $from): self
    {
        return new self("This expense is already {$from}.");
    }

    public static function subscription(string $message): self
    {
        return new self($message);
    }

    public static function invoice(string $message): self
    {
        return new self($message);
    }
}
