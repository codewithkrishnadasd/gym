<?php

declare(strict_types=1);

namespace App\Support\Tasks;

use App\Models\OrganisationUser;
use Illuminate\Support\Collection;

/**
 * "@Name" in a comment. Names are matched against the organisation's active
 * people, longest first, so "@Priya Nair" is one mention even though names
 * contain spaces.
 */
final class Mentions
{
    /**
     * The people a comment mentions.
     *
     * @param  Collection<int, OrganisationUser>  $people
     * @return Collection<int, OrganisationUser>
     */
    public static function extract(string $body, Collection $people): Collection
    {
        $byName = self::byNameLength($people)
            ->filter(fn (OrganisationUser $person): bool => (string) $person->user?->name !== '')
            ->keyBy(fn (OrganisationUser $person): string => mb_strtolower((string) $person->user?->name));

        if ($byName->isEmpty()) {
            return new Collection;
        }

        // One pass, longest name first, so "@Priya Nair" is Priya Nair and
        // never additionally a colleague called just "Priya".
        $alternatives = $byName->keys()->map(fn (string $name): string => preg_quote($name, '/'))->implode('|');

        preg_match_all('/(?:^|[^\w])@('.$alternatives.')(?![\w])/iu', $body, $matches);

        $mentioned = [];

        foreach ($matches[1] as $name) {
            $person = $byName->get(mb_strtolower($name));

            if ($person !== null) {
                $mentioned[$person->id] = $person;
            }
        }

        return new Collection(array_values($mentioned));
    }

    /**
     * The comment as HTML: escaped, line breaks kept, mentions highlighted.
     *
     * @param  Collection<int, OrganisationUser>  $people
     */
    public static function render(string $body, Collection $people): string
    {
        $names = self::byNameLength($people)
            ->map(fn (OrganisationUser $person): string => (string) $person->user?->name)
            ->filter(fn (string $name): bool => $name !== '')
            ->map(fn (string $name): string => preg_quote(e($name), '/'))
            ->values();

        $html = nl2br(e($body));

        if ($names->isEmpty()) {
            return $html;
        }

        // One pass with every name as an alternative, longest first, so a
        // shorter name that is a prefix of a longer one ("Priya" inside
        // "Priya Nair") is never matched a second time inside the highlight.
        return (string) preg_replace_callback(
            '/(^|[^\w])@('.$names->implode('|').')(?![\w])/iu',
            static fn (array $match): string => $match[1].'<span class="rounded bg-accent-soft px-1 font-medium text-accent-ink">@'.$match[2].'</span>',
            $html,
        );
    }

    /**
     * @param  Collection<int, OrganisationUser>  $people
     * @return Collection<int, OrganisationUser>
     */
    private static function byNameLength(Collection $people): Collection
    {
        return $people->sortByDesc(fn (OrganisationUser $person): int => mb_strlen((string) $person->user?->name))->values();
    }
}
