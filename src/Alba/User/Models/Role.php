<?php namespace Alba\User\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
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
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that can be full-text searched
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * The attribute rules that Ardent will validate against
     * 
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'alpha_dash', 'max:32', 'unique:roles'],
    ];

    /**
     * The attribute rules used by seeder
     * 
     * @var array
     */
    public static $rulesForSeeding = ['name'];

    /**
     * The attribute rules used by store()
     * 
     * @var array
     */
    public static $rulesForStoring = ['name'];

    /**
     * The attribute rules used by update()
     * 
     * @var array
     */
    public static $rulesForUpdating = ['name'];

    /**
     * Rules needed for seeding
     * 
     * @return array
     */    
    public function getRulesForSeedingAttribute()
    {
        return array_only(self::$rules, self::$rulesForSeeding);
    }

    /**
     * Rules needed for storing
     * 
     * @return array
     */    
    public function getRulesForStoringAttribute()
    {
        return array_only(self::$rules, self::$rulesForStoring);
    }

    /**
     * Rules needed for updating
     * 
     * @return array
     */
    public function getRulesForUpdatingAttribute()
    {
        $rules = array_only(self::$rules, self::$rulesForUpdating);

        // add exception for the unique constraint
        $key = array_search('unique:roles', $rules['name']);
        $rules['name'][$key] = 'unique:roles,name,' . $this->id;

        return $rules;
    }

    /**
     * Many-to-Many relationship with Permissions
     */
    public function perms() {
        return $this->belongsToMany('\AlbaPermission', 'permission_role', 'role_id');
    }

    /**
     * Alias for perms()
     */
    public function permissions() {
        return $this->perms();
    }

    /**
     * Many-to-Many relationship with Users
     */
    public function users()
    {
        return $this->belongsToMany('\AlbaUser', 'assigned_roles', 'role_id');
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
     * Builds a query scope to return roles of a certain permission
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string|array $permissions ids of permission
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfPermission($query, $permissions)
    {
        // Convert permissions string to array
        if ( is_string($permissions) )
            $permissions = explode(',', $permissions);

        // Separate permission names from permission ids
        $permissionNames = [];
        foreach($permissions as $i => $permission)
        {
            if(!is_numeric($permission))
            {
                $permissionNames[] = $permission;
                unset($permissions[$i]);
            }
        }

        // Get permission ids by querying permission names and merge
        $permissionIds = $permissions;
        if(!empty($permissionNames))
        {
            $permissions = \AlbaPermission::whereIn('name', $permissionNames)->lists('id');
            $permissionIds = array_values(array_unique(array_merge($permissionIds, $permissions), SORT_NUMERIC));
        }

        // Query the permission_role pivot table for matching permissions
        return $query->addSelect(['permission_role.permission_id'])
            ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->whereIn('permission_role.permission_id', $permissionIds)
            ->groupBy('roles.id');
    }
}