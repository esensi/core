<?php

namespace Esensi\Core\Traits;

use Esensi\Core\Traits\FilterableRepositoryTrait;

/**
 * Trait implementation of queried repository interface.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
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
     * @param  array $filters (optional)
     * @return Illuminate\Database\Query\Builder
     */
    public function query( array $filters = [] )
    {
        // Merge the argument filters with the base repository filters
        $this->mergeFilters($filters);
        $filters = $this->getFilters();

        // Join the names table when needed
        if(isset($filters['order']) && in_array($filters['order'], ['sort_name', 'first_name', 'last_name']))
        {
            array_set($filters, 'scopes.joinNames', []);
            $this->mergeFilters($filters);
        }

        // Join the profiles table when needed
        if(isset($filters['order']) && in_array($filters['order'], ['position', 'organization']))
        {
            array_set($filters, 'scopes.joinProfiles', []);
            $this->mergeFilters($filters);
        }

        // Select the number of datasheets and surveys per user
        array_set($filters, 'scopes.selectDatasheets', []);
        array_set($filters, 'scopes.selectSurveys', []);
        $this->mergeFilters($filters);

        // Add additional scope filters
        $this->addBooleanScope('active', array_get($filters, 'active'));
        $this->addBooleanScope('blocked', array_get($filters, 'blocked'));
        $this->addScope('ofRole', [ array_get($filters, 'roles') ]);
        $this->addScope('byName', [ array_get($filters, 'names') ]);
        $this->addScope('ofSurvey', [ array_get($filters, 'surveys') ]);

        // Bind directory status filter
        $role = 'directory';
        $include = array_get($filters, 'directory');
        if( ! is_null($include) && $include !== '' )
        {
            $include = $include === 'false' || $include === false || $include === 0 ? false : true;
            $this->addScope('havingRole', [ $role, $include ]);
        }

        // Setup user query and run the filters
        $query = $this->getModel()->query();
        return $this->filter($query);
    }

}
