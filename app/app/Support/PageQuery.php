<?php

declare(strict_types=1);

namespace App\Support;

/**
 * The query string of the page a Livewire component is on.
 *
 * On the first request that is simply the request's own query. Pages are
 * deferred (App\Livewire\Concerns\LazyPage): the shell comes back at once
 * and the component mounts on a follow-up request to Livewire's update
 * endpoint, whose own query string is empty — the page's is on the Referer,
 * which is also where Livewire's own #[Url] reads it from then.
 */
final class PageQuery
{
    /**
     * @return array<array-key, mixed>
     */
    public static function all(): array
    {
        if (! app('livewire')->isLivewireRequest()) {
            return request()->query();
        }

        $referer = (string) request()->header('Referer', '');
        $query = [];

        parse_str((string) (parse_url($referer, PHP_URL_QUERY) ?? ''), $query);

        return $query;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, self::all());
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    /** A positive integer parameter, or null when absent or not one. */
    public static function integer(string $key): ?int
    {
        $value = self::get($key);

        if (! is_scalar($value) || ! ctype_digit((string) $value) || (int) $value <= 0) {
            return null;
        }

        return (int) $value;
    }
}
