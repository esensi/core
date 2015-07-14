<?php

namespace Esensi\Core\Contracts;

/**
 * Queried repository interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface QueriedRepositoryInterface extends FilterableRepositoryInterface
{
    /**
     * Run filters against repository query.
     *
     * @param  array $filters (optional)
     * @return Illuminate\Database\Query\Builder
     */
    public function query( array $filters = [] );

}
