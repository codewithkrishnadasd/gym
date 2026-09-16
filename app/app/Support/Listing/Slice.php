<?php

declare(strict_types=1);

namespace App\Support\Listing;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * The rows shown so far of a longer list, with what the "load more" control
 * needs: whether more exist, and how many there are in all. Behaves as the
 * collection of rows everywhere else, so views iterate it as before.
 *
 * @template TModel of Model
 *
 * @extends Collection<int, TModel>
 */
final class Slice extends Collection
{
    public bool $hasMore = false;

    public int $total = 0;

    public int $pageSize = 10;
}
