<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Config;

use Alba\Core\Controllers\Resource;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Role;

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
     * Inject dependencies
     *
     * @var Alba\User\Models\Role $role
     * @return RolesResource;
     */
    public function __construct(Role $role)
    {
        $this->model = $role;
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