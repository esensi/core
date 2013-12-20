<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Config;

use Alba\Core\Controllers\Resource;
use Alba\Core\Exceptions\ResourceException;

/**
 * Custom exception handler for RolesResource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Exceptions\ResourceException
 */
class RolesResourceException extends ResourceException {}

/**
 * Roles Resource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Resource
 */
class RolesResource extends Resource {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'role';

    /**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = 'Alba\User\Controllers\RolesResourceException';

    /**
     * The default attributes for searching
     * 
     * @var array $defaults
     */
    protected $defaults = [
        'order' => 'name',
        'sort' => 'asc',
        'max' => 25,
    ];

    /**
     * The Permission model
     *
     * @var Alba\User\Models\Permission
     */
    protected $permission;

    /**
     * Inject dependencies
     *
     * @var Alba\User\Models\Role $role
     * @var Alba\User\Models\Permission $permission
     * @return RolesResource;
     */
    public function __construct(\AlbaRole $role, \AlbaPermission $permission)
    {
        $this->model = $role;
        $this->permission = $permission;
    }

    /**
     * Show the specificed resource by the name
     *
     * @param string $name address
     * @return Model
     */
    public function showByName($name)
    {
        $object = $this->model->whereName($name)->first();
        if(!$object)
        {
            $this->throwException($this->language('errors.show_by_name'));
        }
        return $object;
    }

    /**
     * @see ResourceInterface::update
     */
    public function update($id, $attributes)
    {
        // Update the role
        $object = parent::update($id, array_except($attributes, ['permissions']));

        // Sync the permissions
        $object = $this->syncPermissions($id, $attributes['permissions']);

        return $object;
    }

    /**
     * Synchronize the permissions on the role
     *
     * @param integer $id of role
     * @param array $permissions ids
     * @return Model
     * 
     */
    public function syncPermissions($id, $permissions)
    {
        // Update role attributes
        $object = $this->show($id);
                
        // Sync assigned permissions
        try
        {
            $object->perms()->sync($permissions);
        }
        catch (Exception $e)
        {
            $this->throwException($e->getMessage());
        }

        return $object;
    }

    /**
     * Return an array of roles
     *
     * @return array
     */
    public function names()
    {
        $ttl = Config::get('alba::role.ttl.names', 10);
        return $this->model->whereNotNull('name')->distinct()->remember($ttl)->lists('name');
    }
}