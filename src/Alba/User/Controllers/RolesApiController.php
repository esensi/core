<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing RolesResource as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\ApiController
 * @see Alba\User\Resources\RolesResource
 * @see Alba\User\Controllers\RolesApiController
 */
class RolesApiController extends \AlbaCoreApiController {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'role';

    /**
     * Inject dependencies
     *
     * @param RolesResource $resource;
     * @return void
     */
	public function __construct(\AlbaRolesResource $resource)
	{
		$this->setResource($resource);
	}

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $params = Input::only('max', 'order', 'sort', 'keywords');

        // Filter by permission
        $this->setupArrayTypeScope($params, 'permissions', 'ofPermission');

        return $this->getResource()->index($params);
    }

    /**
     * Display the specified resource by name.
     *
     * @param string $name of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showByName($name)
    {
        return $this->getResource()->showByName($name);
    }

    /**
     * Display the specified resource with users.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showUsers($id)
    {
        $object = $this->getResource()->show($id);
        $object->load('users');
        return $object;
    }

    /**
     * Display the specified resource with permissions.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showPermissions($id)
    {
        $object = $this->getResource()->show($id);
        $object->load('perms');
        return $object;
    }

    /**
     * Update the permissions attached to the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Illuminate\Database\Eloquent\Model
     */
    public function assignPermissions($id)
    {  
        // Convert permissions to array
        $permissions = Input::get('permissions', []);
        if(is_string($permissions))
            $permissions = explode(',', $permissions);

        // Sync permissions
        $object = $this->getResource()->syncPermissions($id, $permissions);
        return $object;
    }

    /**
     * Display a list of all roles
     *
     * @return array
     */
    public function names()
    {
        return $this->getResource()->names();
    }

}