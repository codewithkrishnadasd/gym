<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Builder;

/**
 * The one way every list is searched: by name, by phone number, or by the
 * reference the organisation gave the record ("MEM-42"). Always a contains
 * match, case-insensitive, on whatever was typed.
 *
 * Phone matching strips everything that is not a digit from the search
 * term — "98765 43210" and "+91 98765" both find the number — but only when
 * the term actually contains digits, so a name search never collapses to an
 * empty pattern that matches every row.
 */
final class Search
{
    /**
     * Whether the term looks like a reference for this kind of record, and if
     * so which id it names. Accepts the full form ("MEM-42", "mem 42") and a
     * bare number.
     */
    public static function referenceId(Organisation $organisation, string $entity, string $term): ?int
    {
        $term = trim($term);

        if (self::isReference($organisation, $entity, $term)) {
            return (int) preg_replace('/\D+/', '', $term);
        }

        if (preg_match('/^#?0*(\d{1,9})$/', $term, $match) === 1) {
            return (int) $match[1];
        }

        return null;
    }

    /**
     * Whether the term is written as this record's reference ("MEM-42",
     * "mem 42"). Such a term is an id and nothing else — its digits are not a
     * phone number fragment.
     */
    public static function isReference(Organisation $organisation, string $entity, string $term): bool
    {
        $prefix = preg_quote($organisation->idPrefix($entity), '/');

        return preg_match('/^'.$prefix.'[\s-]*0*\d+$/i', trim($term)) === 1;
    }

    /**
     * Digits of the term to match a phone on, or null when there are none —
     * or when the term is a reference, whose digits mean something else.
     */
    public static function phoneDigits(string $term, ?Organisation $organisation = null, ?string $entity = null): ?string
    {
        if ($organisation !== null && $entity !== null && self::isReference($organisation, $entity, $term)) {
            return null;
        }

        $digits = PhoneNumber::searchable($term);

        return $digits === '' ? null : $digits;
    }

    /**
     * Applies the standard search to a query over a table that has its own
     * `name`, `phone` and `id` columns.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @param  array<int, string>  $textColumns  columns matched with *term*
     * @return Builder<TModel>
     */
    public static function apply(Builder $query, Organisation $organisation, string $entity, string $term, array $textColumns = ['name'], ?string $phoneColumn = 'phone', string $idColumn = 'id'): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        $digits = self::phoneDigits($term, $organisation, $entity);
        $id = self::referenceId($organisation, $entity, $term);

        return $query->where(function (Builder $inner) use ($term, $digits, $id, $textColumns, $phoneColumn, $idColumn): void {
            foreach ($textColumns as $column) {
                $inner->orWhere($column, 'ilike', '%'.$term.'%');
            }

            if ($phoneColumn !== null && $digits !== null) {
                $inner->orWhere($phoneColumn, 'ilike', '%'.$digits.'%');
            }

            if ($id !== null) {
                $inner->orWhere($idColumn, $id);
            }
        });
    }
}
