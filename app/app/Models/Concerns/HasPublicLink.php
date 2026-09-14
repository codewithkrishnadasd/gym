<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * A shareable, unauthenticated link to this record's document (an invoice or
 * a receipt). The token is created on first use and never changes, so a link
 * sent on WhatsApp keeps working.
 *
 * Models using this define `publicRouteName()`: the route that takes `{token}`.
 */
trait HasPublicLink
{
    abstract protected function publicRouteName(): string;

    public function publicToken(): string
    {
        if ($this->public_token === null) {
            $this->forceFill(['public_token' => Str::random(40)])->save();
        }

        return (string) $this->public_token;
    }

    /**
     * Absolute URL on the organisation's own domain — the one the member sees.
     * Built from the organisation's primary hostname rather than the current
     * request, so a link composed from a queue or the console reads the same.
     */
    public function publicUrl(): string
    {
        $path = route($this->publicRouteName(), ['token' => $this->publicToken()], false);
        $host = $this->organisation?->primaryHostname();

        if ($host === null) {
            return url($path);
        }

        $scheme = str_starts_with((string) config('app.url'), 'https') ? 'https' : 'http';

        return $scheme.'://'.$host.$path;
    }
}
