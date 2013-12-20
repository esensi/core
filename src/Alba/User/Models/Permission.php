<?php namespace Alba\User\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Collection;
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
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name',
    ];

    /**
     * The attributes that can be full-text searched
     *
     * @var array
     */
    public $searchable = ['name', 'display_name'];

    /**
     * The attribute rules that Ardent will validate against
     * 
     * @var array
     */
    public static $rules = [
        'name' => ['required', 'alpha_dash', 'max:32', 'unique:permissions'],
        'display_name' => ['required', 'max:32'],
    ];

    /**
     * The attribute rules used by seeder
     * 
     * @var array
     */
    public static $rulesForSeeding = ['name', 'display_name'];

    /**
     * The attribute rules used by store()
     * 
     * @var array
     */
    public static $rulesForStoring = ['name', 'display_name'];

    /**
     * The attribute rules used by update()
     * 
     * @var array
     */
    public static $rulesForUpdating = ['display_name'];

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
        return array_only(self::$rules, self::$rulesForUpdating);
    }

    /**
     * Many-to-Many relations with Roles
     */
    public function roles()
    {
        return $this->belongsToMany('\AlbaRole', 'permission_role', 'permission_id');
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
     * Returns the users for the current permission
     * 
     * @return Collection
     */
    public function getUsersAttribute()
    {
        $collection = new Collection;
        
        foreach($this->roles as $role)
        {
           $collection = $collection->merge($role->users);
        }
        
        return $collection;
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
            $roles = \AlbaRole::whereIn('name', $roleNames)->lists('id');
            $roleIds = array_values(array_unique(array_merge($roleIds, $roles), SORT_NUMERIC));
        }

        // Query the permission_role pivot table for matching roles
        return $query->addSelect(['permission_role.role_id'])
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->whereIn('permission_role.role_id', $roleIds)
            ->groupBy('permissions.id');
    }

}