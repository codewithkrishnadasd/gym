<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\PasswordResetLink;

/**
 * The one moment the plain token exists. It is never stored and never read
 * back, so anything that needs to show or send the link has to do it from
 * this object.
 */
final class IssuedResetLink
{
    public function __construct(
        public readonly PasswordResetLink $link,
        public readonly string $url,
        public readonly int $expiresInMinutes,
    ) {}

    public function expiresLabel(): string
    {
        return $this->expiresInMinutes >= 60 && $this->expiresInMinutes % 60 === 0
            ? ($this->expiresInMinutes / 60).' hour'.($this->expiresInMinutes === 60 ? '' : 's')
            : $this->expiresInMinutes.' minutes';
    }
}
