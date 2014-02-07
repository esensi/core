<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing PermissionResource as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\ApiController
 * @see Alba\User\Resources\PermissionsResource
 */
class PermissionsApiController extends \AlbaCoreApiController {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'permission';
    
    /**
     * Inject dependencies
     *
     * @param PermissionsResource $resource;
     * @return void
     */
	public function __construct(\AlbaPermissionsResource $resource)
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

        // Filter by role
        $this->setupArrayTypeScope($params, 'roles', 'ofRole');
        
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
     * Display the specified resource with permissions.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showRoles($id)
    {
        $object = $this->getResource()->show($id);
        $object->load('roles');
        return $object;
    }

    /**
     * Display a list of all permissions.
     *
     * @return array
     */
    public function names()
    {
        return $this->getResource()->names();
    }

}