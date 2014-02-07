<?php namespace Alba\User\Resources;

use Illuminate\Support\Facades\Config;

/**
 * Custom exception handler for RolesResource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Exceptions\ResourceException
 */
class RolesResourceException extends \AlbaCoreResourceException {}

/**
 * Roles Resource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Resources\Resource
 */
class RolesResource extends \AlbaCoreResource {

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
    protected $exception = 'Alba\User\Resources\RolesResourceException';

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
     * Inject dependencies
     *
     * @var Alba\User\Models\Role $model
     * @var Alba\User\Models\Permission $permission
     * @return RolesResource;
     */
    public function __construct(\AlbaRole $model, \AlbaPermission $permission)
    {
        $this->setModel($model);
        $this->setModel($permission, 'permission');
        $this->setDefaults($this->defaults);
    }

    /**
     * Show the specificed resource by the name
     *
     * @param string $name address
     * @return Model
     */
    public function showByName($name)
    {
        $object = $this->getModel()->whereName($name)->first();
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
        return $this->getModel()->whereNotNull('name')->distinct()->remember($ttl)->lists('name');
    }
}