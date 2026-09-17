<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Livewire\Attributes\Url;
use ReflectionClass;
use ReflectionProperty;

/**
 * Keeps a list's filters from one visit to the next.
 *
 * Every filter is already in the URL (`#[Url]`), which survives a refresh
 * and a shared link. It does not survive the menu: "Members" in the sidebar
 * is a bare link, so a list narrowed a minute ago came back wide open. This
 * saves the filters in the session each time one changes and puts them back
 * whenever the list is opened with no filters in the URL — a link that
 * names its own filters (a dashboard card, a count) still wins.
 */
trait RemembersFilters
{
    /**
     * Runs before `#[Url]` reads the query string and before the component's
     * own `mount()`, so a link that names filters still overrides what is
     * restored, and mount() can still correct anything a switched-off module
     * makes meaningless.
     */
    public function initializeRemembersFilters(): void
    {
        $keys = $this->rememberedFilterKeys();

        // Anything filter-like in the URL means the caller chose the view.
        foreach ($keys as $key) {
            if (request()->query->has($key)) {
                return;
            }
        }

        /** @var array<string, mixed> $saved */
        $saved = session()->get($this->rememberedFiltersSessionKey(), []);

        foreach ($saved as $key => $value) {
            if (in_array($key, $keys, true) && is_string($value)) {
                $this->{$key} = $value;
            }
        }
    }

    public function updatedRemembersFilters(string $property, mixed $value): void
    {
        if (! in_array($property, $this->rememberedFilterKeys(), true)) {
            return;
        }

        $current = [];

        foreach ($this->rememberedFilterKeys() as $key) {
            $current[$key] = $this->{$key};
        }

        session()->put($this->rememberedFiltersSessionKey(), $current);
    }

    /**
     * Every URL-bound string property except the page number: the filters,
     * the search box, and the tab or date range where a list has them.
     *
     * @return array<int, string>
     */
    protected function rememberedFilterKeys(): array
    {
        $keys = [];

        foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->getAttributes(Url::class) === [] || $property->getName() === 'page') {
                continue;
            }

            $type = $property->getType();

            if ($type instanceof \ReflectionNamedType && $type->getName() === 'string') {
                $keys[] = $property->getName();
            }
        }

        return $keys;
    }

    /**
     * Whether this person has ever changed a filter on this list — the point
     * after which their own choice, not a built-in default, is what to show.
     */
    protected function hasRememberedFilters(): bool
    {
        return session()->has($this->rememberedFiltersSessionKey());
    }

    private function rememberedFiltersSessionKey(): string
    {
        $tenant = app()->bound('tenant') ? app('tenant')->id : 'platform';

        return 'filters.'.$tenant.'.'.static::class;
    }
}
