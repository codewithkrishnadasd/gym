<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PasswordResetLink;
use App\Models\PlatformAdmin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Issues a short-lived, single-use link that lets someone set their own
 * password (MEP.md 3.2).
 *
 * This replaces generating a password on the admin's screen. The difference
 * that matters is not convenience: a generated password is known to whoever
 * generated it, travels through a chat unchanged, and stays valid until
 * somebody thinks to change it. A link expires on its own, works once, and the
 * admin never learns what the password ends up being.
 */
final class IssuePasswordResetLink
{
    public function handle(
        Organisation $organisation,
        User $user,
        OrganisationUser|PlatformAdmin $issuedBy,
    ): IssuedResetLink {
        // 64 hex characters from the CSPRNG. Only its hash is stored, so a
        // database dump cannot be turned back into a working link.
        $token = bin2hex(random_bytes(32));

        $link = DB::transaction(function () use ($organisation, $user, $issuedBy, $token): PasswordResetLink {
            // Issuing a new link retires any earlier one. Otherwise every link
            // ever generated for this person stays live until it expires, and
            // an admin re-issuing because "the first one didn't arrive" would
            // widen the window instead of replacing it.
            PasswordResetLink::query()
                ->where('user_id', $user->id)
                ->where('organisation_id', $organisation->id)
                ->outstanding()
                ->update(['used_at' => now()]);

            return PasswordResetLink::create([
                'organisation_id' => $organisation->id,
                'user_id' => $user->id,
                'token_hash' => hash('sha256', $token),
                'expires_at' => now()->addMinutes(self::lifetimeMinutes()),
                'issued_by_organisation_user_id' => $issuedBy instanceof OrganisationUser ? $issuedBy->id : null,
                'issued_by_platform_admin_id' => $issuedBy instanceof PlatformAdmin ? $issuedBy->id : null,
            ]);
        });

        return new IssuedResetLink(
            link: $link,
            url: self::urlFor($organisation, $token),
            expiresInMinutes: self::lifetimeMinutes(),
        );
    }

    public static function lifetimeMinutes(): int
    {
        return max(5, (int) config('platform.password_reset_link_minutes', 60));
    }

    /**
     * Built from the organisation's own hostname rather than the current
     * request: a platform admin issues these from the platform domain, and a
     * link pointing there would never reach a tenant login.
     */
    private static function urlFor(Organisation $organisation, string $token): string
    {
        $hostname = $organisation->primaryHostname();

        if ($hostname === null) {
            // No domain mapped yet. A relative path is still correct once one
            // is, and is obviously incomplete rather than silently wrong.
            return '/set-password/'.$token;
        }

        return Str::of(config('app.url'))->startsWith('https://')
            ? 'https://'.$hostname.'/set-password/'.$token
            : 'http://'.$hostname.'/set-password/'.$token;
    }
}
