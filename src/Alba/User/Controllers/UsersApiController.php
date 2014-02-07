<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing UsersResource as an API
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\ApiController
 * @see Alba\User\Resources\UsersResource
 */
class UsersApiController extends \AlbaCoreApiController {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'user';

    /**
     * Inject dependencies
     *
     * @param UsersResource $resource;
     * @return void
     */
	public function __construct(\AlbaUsersResource $resource)
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
        $params = Input::only('max', 'order', 'sort', 'keywords', 'trashed');

        // Join the names table when needed
        if(in_array($params['order'], ['sort_name', 'first_name', 'last_name']))
        {
            $params['scopes']['joinNames'] = [];
        }

        // Filter by active status
        $active = Input::get('active', null);
        if( is_numeric($active) && $active >= 0 && $active <= 1)
        {
            $params['active'] = $active;
            $params['scopes']['whereActive'] = [ (int) $active ];
        }

        // Filter by blocked status
        $blocked = Input::get('blocked', null);
        if( is_numeric($blocked) && $blocked >= 0 && $blocked <= 1)
        {
            $params['blocked'] = $blocked;
            $params['scopes']['whereBlocked'] = [ (int) $blocked ];
        }

        // Filter by role
        $this->setupArrayTypeScope($params, 'roles', 'ofRole');

        // Filter by name
        $this->setupArrayTypeScope($params, 'names', 'byName');
        
        return $this->getResource()->index($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param boolean $twoStep authentication
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store($twoStep = true)
    {
        $attributes = Input::all();
        $object = $this->getResource()->store($attributes);

        // Two-step activation
        if($twoStep)
        {
            $this->getResource()->resetActivation($object->email, true);
        }

        return $object;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of object
     * @param boolean $withTrashed
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id, $withTrashed = false)
    {
        return $this->getResource()->show($id, $withTrashed);
    }

    /**
     * Update the roles attached to the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Illuminate\Database\Eloquent\Model
     */
    public function assignRoles($id)
    {  
        // Convert roles to array
        $roles = Input::get('roles', []);
        if(is_string($roles))
            $roles = explode(',', $roles);

        // Sync roles
        $object = $this->getResource()->syncRoles($id, $roles);
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
        $force = Input::get('force');
        return $this->getResource()->destroy($id, $force);
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param int $id of object to remove
     * @return Illuminate\Database\Eloquent\Model
     * 
     */
    public function restore($id)
    {
        return $this->getResource()->restore($id);
    }

    /**
     * Display a list of all available titles
     *
     * @return array
     */
    public function titles()
    {
        return $this->getResource()->titles();
    }

    /**
     * Display a list of all available suffixes
     *
     * @return array
     */
    public function suffixes()
    {
        return $this->getResource()->suffixes();
    }

}