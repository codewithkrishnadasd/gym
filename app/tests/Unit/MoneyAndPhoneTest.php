<?php

declare(strict_types=1);

use App\Support\Money;
use App\Support\PhoneNumber;

/**
 * Money is stored in minor units and phone numbers must reach `wa.me` as bare
 * international digits (MEP.md 5.11, 10) — both are pure logic worth pinning
 * down precisely.
 */
it('formats minor units without ever using floats for storage', function (): void {
    expect(Money::ofMinor(123456, 'INR')->major())->toBe(1234.56)
        ->and(Money::ofMinor(0, 'INR')->major())->toBe(0.0)
        // Zero-decimal currencies must not be divided by 100.
        ->and(Money::ofMinor(1500, 'JPY')->major())->toBe(1500.0);
});

it('puts the minus sign outside the currency symbol in compact form', function (): void {
    expect(Money::ofMinor(-8810300, 'INR')->formatCompact())->toStartWith('-')
        ->and(Money::ofMinor(-8810300, 'INR')->formatCompact())->not->toContain('₹-')
        ->and(Money::ofMinor(8810300, 'INR')->formatCompact())->not->toStartWith('-');
});

it('parses operator-entered amounts into minor units', function (): void {
    expect(Money::parseMajor('1,250.50', 'INR')?->minor)->toBe(125050)
        ->and(Money::parseMajor('  99 ', 'INR')?->minor)->toBe(9900)
        ->and(Money::parseMajor('1500', 'JPY')?->minor)->toBe(1500)
        ->and(Money::parseMajor('', 'INR'))->toBeNull()
        ->and(Money::parseMajor('abc', 'INR'))->toBeNull();
});

it('normalises phone numbers to bare international digits', function (string $input, ?string $expected): void {
    expect(PhoneNumber::normalise($input, 'IN'))->toBe($expected);
})->with([
    'local with spaces' => ['98765 43210', '919876543210'],
    'local with trunk zero' => ['098765 43210', '919876543210'],
    'already international' => ['+91 98765 43210', '919876543210'],
    'double-zero prefix' => ['00919876543210', '919876543210'],
    'punctuated' => ['(98765)-43210', '919876543210'],
    'already prefixed, no plus' => ['919876543210', '919876543210'],
    'too short' => ['12345', null],
    'not a number' => ['not-a-number', null],
    'empty' => ['', null],
]);

it('does not mistake a national number for one that already has a country code', function (): void {
    // 9111111111 is a valid Indian mobile that happens to start with 91,
    // India's own calling code. Deciding by prefix alone dropped the country
    // code and stored a different number than the one dialled.
    expect(PhoneNumber::normalise('9111111111', 'IN'))->toBe('919111111111')
        ->and(PhoneNumber::normalise('919111111111', 'IN'))->toBe('919111111111')
        // Both spellings must land on the same stored identity.
        ->and(PhoneNumber::normalise('9111111111', 'IN'))
        ->toBe(PhoneNumber::normalise('+91 91111 11111', 'IN'));
});

it('builds a correctly encoded WhatsApp deep link', function (): void {
    $url = PhoneNumber::whatsappUrl('98765 43210', "Hi Aditi,\nYour fee of ₹1,500 is confirmed.", 'IN');

    expect($url)->toStartWith('https://wa.me/919876543210?text=')
        // No spaces, punctuation, or leading + in the path (MEP.md 5.11.1).
        ->and($url)->toContain('wa.me/919876543210')
        ->and($url)->not->toContain(' ')
        ->and(urldecode((string) $url))->toContain('Hi Aditi,');
});

it('returns no WhatsApp link when the number is unusable', function (): void {
    expect(PhoneNumber::whatsappUrl(null, 'hi', 'IN'))->toBeNull()
        ->and(PhoneNumber::whatsappUrl('123', 'hi', 'IN'))->toBeNull();
});

it('applies the organisations country when the number is national', function (): void {
    expect(PhoneNumber::normalise('7700 900123', 'GB'))->toBe('447700900123')
        ->and(PhoneNumber::normalise('(555) 010-9999', 'US'))->toBe('15550109999');
});
