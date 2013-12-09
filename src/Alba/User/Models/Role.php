<?php namespace Alba\User\Models;

use Zizaco\Entrust\EntrustRole;

/**
 * Alba\Role model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Models\Permission
 * @see Alba\User\Models\User
 */
class Role extends EntrustRole {

    /**
     * Method added to solve bug:
     *
     * PHP Fatal error:  Class 'Permission' not found in ...../bootstrap/compiled.php
     *
     * 
     * Many-to-Many relations with Permission
     * named perms as permissions is already taken.
     */
    public function perms() {
        // To maintain backwards compatibility we'll catch the exception if the Permission table doesn't exist.
        // TODO remove in a future version
        try {
            return $this->belongsToMany('Alba\User\Models\Permission');
        } catch(Execption $e) {}
    }

    /**
     * Builds a query scope to return roles alphabetically for a dropdown list
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $column to order by
     * @param string $key to use in returned array
     * @param string $sort direction
     * @return array [$key => $name]
     */
    public function scopeListAlphabetically($query, $column = 'name', $key = 'id', $sort = 'asc')
    {
        return $query->orderBy($column, $sort)->lists($column, $key);
    }
}