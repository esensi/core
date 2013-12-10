<?php namespace Alba\User\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Zizaco\Entrust\EntrustPermission;

/**
 * Alba\Permission model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Models\Role
 */
class Permission extends EntrustPermission {

    /**
     * Many-to-Many relations with Roles
     */
    public function roles()
    {
        return $this->belongsToMany('Alba\User\Models\Role');
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

    /**
     * Returns the number of minutes since the creation time
     *
     * @return string
     */
    public function getTimeSinceCreatedAttribute()
    {
        // Short circuit for models that have not been created
        if( is_null($this->created_at) )
        {
            return Lang::get('alba::core.messages.never_created');
        }

        $date = new Carbon($this->created_at);
        return $date->diffForHumans();
    }

    /**
     * Returns the number of minutes since the update time
     *
     * @return string
     */
    public function getTimeSinceUpdatedAttribute()
    {
        // Short circuit for models that have not been updated
        if( is_null($this->updated_at) )
        {
            return Lang::get('alba::core.messages.never_updated');
        }

        $date = new Carbon($this->updated_at);
        return $date->diffForHumans();
    }

    /**
     * Builds a query scope to return permissions of a certain role
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string|array $roles ids of role
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfRole($query, $roles)
    {
        // Convert roles string to array
        if ( is_string($roles) )
            $roles = explode(',', $roles);

        // Separate role names from role ids
        $rolesNames = [];
        foreach($roles as $i => $role)
        {
            if(!is_numeric($role))
            {
                $roleNames[] = $role;
                unset($roles[$i]);
            }
        }

        // Get role ids by querying role names and merge
        $roleIds = $roles;
        if(!empty($roleNames))
        {
            $roles = Role::whereIn('name', $roleNames)->lists('id');
            $roleIds = array_values(array_unique(array_merge($roleIds, $roles), SORT_NUMERIC));
        }

        // Query the permission_role pivot table for matching roles
        return $query->addSelect(['permission_role.role_id'])
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->whereIn('permission_role.role_id', $roleIds)
            ->groupBy('permissions.id');
    }

}