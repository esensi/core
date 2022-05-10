<?php

namespace Esensi\Core\Contracts;

/**
 * Queried repository interface
 *
 */
interface QueriedRepositoryInterface extends FilterableRepositoryInterface
{
    /**
     * Run filters against repository query.
     *
     * @param  array  $filters (optional)
     * @return Illuminate\Database\Query\Builder
     */
    public function query( array $filters = [] );

}
