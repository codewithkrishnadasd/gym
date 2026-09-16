<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Support\Listing\Slice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Lists grow as the reader scrolls instead of paging. The first screen
 * shows a handful of rows; reaching the bottom (or tapping "Load more")
 * asks for the next handful, and a filter change starts over. Each request
 * re-renders the rows shown so far, keyed by row so the browser only adds
 * the new ones.
 */
trait LoadsMore
{
    /** How many rows are shown right now. */
    public int $limit = 0;

    public function initializeLoadsMore(): void
    {
        if ($this->limit <= 0) {
            $this->limit = $this->pageSize();
        }
    }

    /** Rows per step. Components override for denser or sparser lists. */
    protected function pageSize(): int
    {
        return 10;
    }

    public function loadMore(): void
    {
        $this->limit += $this->pageSize();
    }

    /**
     * Back to the first screen — the name pagination used, so the existing
     * calls on every filter change keep working unchanged.
     */
    public function resetPage(): void
    {
        $this->limit = $this->pageSize();
    }

    /**
     * Runs the query for the rows shown so far. One row more than the limit
     * is asked for, to know whether another step exists without a second
     * count; the total is counted separately for the "N of M" line.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Slice<TModel>
     */
    protected function slice(Builder $query): Slice
    {
        $total = (int) $query->clone()->toBase()->getCountForPagination();

        $rows = $query->limit($this->limit + 1)->get();

        /** @var Slice<TModel> $slice */
        $slice = new Slice($rows->take($this->limit)->all());
        $slice->hasMore = $rows->count() > $this->limit;
        $slice->total = $total;
        $slice->pageSize = $this->pageSize();

        return $slice;
    }
}
