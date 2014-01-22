<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
use Alba\Core\Controllers\Controller;

/**
 * Controller for accessing PermissionResource as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Resources\PermissionsResource
 */
class PermissionsApiController extends Controller {

    /**
     * Inject dependencies
     *
     * @param PermissionsResource $permissionsResource;
     * @return void
     */
	public function __construct(\AlbaPermissionsResource $permissionsResource)
	{
		$this->resources['permission'] = $permissionsResource;
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
        
        return $this->resources['permission']->index($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $attributes = Input::all();
        return $this->resources['permission']->store($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->resources['permission']->show($id);
    }

    /**
     * Display the specified resource by name.
     *
     * @param string $name of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showByName($name)
    {
        return $this->resources['permission']->showByName($name);
    }

    /**
     * Display the specified resource with permissions.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showRoles($id)
    {
        $object = $this->resources['permission']->show($id);
        $object->load('roles');
        return $object;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id)
    {
        $attributes = Input::all();
        $object = $this->resources['permission']->update($id, $attributes);
        return $object;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id of object to remove
     * @param bool $force delete
     * @return bool
     * 
     */
    public function destroy($id)
    {
        return $this->resources['permission']->destroy($id);
    }

    /**
     * Display a list of all permissions.
     *
     * @return array
     */
    public function names()
    {
        return $this->resources['permission']->names();
    }

}