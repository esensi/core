<?php

namespace Esensi\Core\Traits;

use Esensi\Core\Traits\FilterableRepositoryTrait;

/**
 * Trait implementation of queried repository interface.
 *
 * @see Esensi\Core\Contracts\QueriedRepositoryInterface
 */
trait QueriedRepositoryTrait
{
    /**
     * Make this repository use filter methods.
     *
     * @see Esensi\Core\Traits\FilterableRepositoryTrait
     */
    use FilterableRepositoryTrait;

    /**
     * Run filters against repository query.
     *
     * @param  array  $filters (optional)
     * @return Illuminate\Database\Query\Builder
     */
    public function query(array $filters = [])
    {
        // Merge the argument filters with the base repository filters
        $this->mergeFilters($filters);

        // Setup user query and run the filters
        $query = $this->getModel()->query();
        return $this->filter($query);
    }

}
