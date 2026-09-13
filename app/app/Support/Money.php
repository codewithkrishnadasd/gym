<?php

declare(strict_types=1);

namespace App\Support;

use NumberFormatter;

/**
 * Money is stored everywhere as an integer count of the smallest currency
 * unit (`*_minor` columns) so no financial total ever passes through a float.
 * This class is the only place minor units become display text.
 */
final class Money
{
    private function __construct(
        public readonly int $minor,
        public readonly string $currencyCode,
    ) {}

    public static function ofMinor(int $minor, string $currencyCode): self
    {
        return new self($minor, strtoupper($currencyCode));
    }

    /**
     * Parses operator-entered major units ("1,250.50") into minor units.
     * Returns null when the input is not a usable amount.
     */
    public static function parseMajor(string $input, string $currencyCode): ?self
    {
        $normalised = str_replace([',', ' ', "\u{00A0}"], '', trim($input));

        if ($normalised === '' || ! is_numeric($normalised)) {
            return null;
        }

        $factor = 10 ** self::fractionDigits($currencyCode);

        return new self((int) round(((float) $normalised) * $factor), strtoupper($currencyCode));
    }

    public function major(): float
    {
        return $this->minor / (10 ** self::fractionDigits($this->currencyCode));
    }

    /**
     * Full localised representation including the currency symbol.
     */
    public function format(string $locale = 'en'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        $formatted = $formatter->formatCurrency($this->major(), $this->currencyCode);

        return $formatted === false
            ? $this->currencyCode.' '.number_format($this->major(), self::fractionDigits($this->currencyCode))
            : $formatted;
    }

    /**
     * Digits only — for table columns that already carry a currency header.
     */
    public function formatPlain(string $locale = 'en'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, self::fractionDigits($this->currencyCode));
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, self::fractionDigits($this->currencyCode));

        $formatted = $formatter->format($this->major());

        return $formatted === false ? (string) $this->major() : $formatted;
    }

    /**
     * Compact form for dashboard cards where column width is scarce.
     */
    public function formatCompact(string $locale = 'en'): string
    {
        $major = $this->major();
        $symbol = self::symbol($this->currencyCode, $locale);

        // The sign belongs outside the currency symbol.
        $sign = $major < 0 ? '-' : '';
        $magnitude = abs($major);

        foreach ([['T', 1_000_000_000_000], ['B', 1_000_000_000], ['M', 1_000_000], ['K', 1_000]] as [$suffix, $threshold]) {
            if ($magnitude >= $threshold) {
                return $sign.$symbol.rtrim(rtrim(number_format($magnitude / $threshold, 1), '0'), '.').$suffix;
            }
        }

        return $sign.$symbol.number_format($magnitude, $magnitude < 100 ? self::fractionDigits($this->currencyCode) : 0);
    }

    public static function symbol(string $currencyCode, string $locale = 'en'): string
    {
        $formatter = new NumberFormatter($locale.'@currency='.strtoupper($currencyCode), NumberFormatter::CURRENCY);

        return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL) ?: strtoupper($currencyCode);
    }

    /**
     * Zero-decimal currencies (JPY, KRW, VND...) must not be divided by 100.
     */
    public static function fractionDigits(string $currencyCode): int
    {
        return in_array(strtoupper($currencyCode), ['JPY', 'KRW', 'VND', 'CLP', 'ISK', 'UGX', 'XAF', 'XOF'], true) ? 0 : 2;
    }
}
