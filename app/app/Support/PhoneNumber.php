<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Normalises operator-entered phone numbers to bare international digits
 * (E.164 without the leading `+`), which is the exact shape `wa.me/{number}`
 * requires — MEP.md Section 10 forbids building a deep link from an
 * un-normalised number.
 *
 * Numbers are also normalised before search and duplicate detection, so this
 * class is used by Form Requests, search queries, and message builders alike
 * rather than each re-implementing the rules.
 */
final class PhoneNumber
{
    /**
     * ISO 3166-1 alpha-2 => international dialling code, for the countries an
     * organisation can be configured with.
     *
     * @var array<string, string>
     */
    private const DIALLING_CODES = [
        'AE' => '971', 'AU' => '61', 'BD' => '880', 'BE' => '32', 'BR' => '55',
        'CA' => '1', 'CH' => '41', 'DE' => '49', 'DK' => '45', 'EG' => '20',
        'ES' => '34', 'FR' => '33', 'GB' => '44', 'ID' => '62', 'IE' => '353',
        'IN' => '91', 'IT' => '39', 'JP' => '81', 'KE' => '254', 'LK' => '94',
        'MY' => '60', 'NG' => '234', 'NL' => '31', 'NP' => '977', 'NZ' => '64',
        'OM' => '968', 'PH' => '63', 'PK' => '92', 'PL' => '48', 'PT' => '351',
        'QA' => '974', 'SA' => '966', 'SE' => '46', 'SG' => '65', 'TH' => '66',
        'TR' => '90', 'US' => '1', 'VN' => '84', 'ZA' => '27',
    ];

    /** Shortest and longest total digit count permitted by E.164. */
    private const MIN_DIGITS = 8;

    private const MAX_DIGITS = 15;

    /**
     * Returns bare international digits, or null when the input cannot be
     * turned into a plausible number. Never throws — a bad number must not
     * block the business action that triggered the message (MEP.md 10).
     */
    public static function normalise(?string $raw, string $defaultCountry = 'IN'): ?string
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $callingCode = self::callingCodeFor($defaultCountry);
        $hasPlus = str_starts_with(ltrim($raw), '+');
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if ($digits === '') {
            return null;
        }

        // "00" is the other way of writing "+" in most of the world.
        if (! $hasPlus && str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
            $hasPlus = true;
        }

        if (! $hasPlus) {
            // A single leading zero is a national trunk prefix, never part of
            // the international number.
            $national = ltrim($digits, '0');

            $digits = str_starts_with($digits, '0') || ! str_starts_with($digits, $callingCode)
                ? $callingCode.$national
                : $digits;
        }

        return self::isPlausible($digits) ? $digits : null;
    }

    public static function isValid(?string $raw, string $defaultCountry = 'IN'): bool
    {
        return self::normalise($raw, $defaultCountry) !== null;
    }

    /**
     * Human-readable form for display in the UI, e.g. "+91 98765 43210".
     */
    public static function forDisplay(?string $raw, string $defaultCountry = 'IN'): ?string
    {
        $normalised = self::normalise($raw, $defaultCountry);

        if ($normalised === null) {
            return $raw === null || trim($raw) === '' ? null : trim($raw);
        }

        $callingCode = self::detectCallingCode($normalised) ?? self::callingCodeFor($defaultCountry);
        $national = substr($normalised, strlen($callingCode));

        $grouped = strlen($national) > 6
            ? trim(chunk_split($national, max(1, (int) ceil(strlen($national) / 2)), ' '))
            : $national;

        return '+'.$callingCode.' '.$grouped;
    }

    /**
     * The `wa.me` deep link for a confirmed action's message. Returns null
     * when the number is unusable so callers can show the copy-only fallback
     * instead of a broken link (MEP.md 5.11.1).
     */
    public static function whatsappUrl(?string $raw, string $message, string $defaultCountry = 'IN'): ?string
    {
        $normalised = self::normalise($raw, $defaultCountry);

        return $normalised === null
            ? null
            : 'https://wa.me/'.$normalised.'?text='.rawurlencode($message);
    }

    /**
     * Digits-only form used for duplicate detection and `LIKE` searching,
     * where the caller may not know the country.
     */
    public static function searchable(?string $raw): string
    {
        return preg_replace('/\D+/', '', (string) $raw) ?? '';
    }

    public static function callingCodeFor(string $country): string
    {
        return self::DIALLING_CODES[strtoupper($country)] ?? '91';
    }

    /**
     * @return array<string, string> ISO country code => "+<dialling code>"
     */
    public static function countries(): array
    {
        return array_map(static fn (string $code): string => '+'.$code, self::DIALLING_CODES);
    }

    private static function detectCallingCode(string $digits): ?string
    {
        // Longest match wins so +1 does not shadow +91.
        $codes = array_unique(array_values(self::DIALLING_CODES));
        usort($codes, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($codes as $code) {
            if (str_starts_with($digits, $code)) {
                return $code;
            }
        }

        return null;
    }

    private static function isPlausible(string $digits): bool
    {
        $length = strlen($digits);

        return $length >= self::MIN_DIGITS
            && $length <= self::MAX_DIGITS
            && ! str_starts_with($digits, '0');
    }
}
