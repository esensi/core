<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
use Alba\Core\Controllers\Controller;
use Alba\User\Controllers\UsersResource;

/**
 * Controller for accessing UsersResource as an API
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Controllers\UsersResource
 */
class UsersApiController extends Controller {

    /**
     * Inject dependencies
     *
     * @param UsersResource $usersResource;
     * @return void
     */
	public function __construct(UsersResource $usersResource)
	{
		$this->resources['user'] = $usersResource;
	}

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $params = Input::only('max', 'order', 'sort', 'keyword');

        // Join the names table when needed
        if(in_array($params['order'], ['name', 'first_name', 'last_name']))
        {
            $params['scopes']['joinNames'] = [];
        }

        // Filter by active status
        $active = Input::input('active', null);
        if( is_numeric($active) )
        {
            $params['active'] = $active;
            $params['scopes']['whereActive'] = [ (int) $active ];
        }

        // Filter by blocked status
        $blocked = Input::get('blocked', null);
        if( is_numeric($blocked) )
        {
            $params['blocked'] = $blocked;
            $params['scopes']['whereBlocked'] = [ (int) $blocked ];
        }

        // Filter by role
        if( $roles = Input::get('roles', false) )
        {
            $params['roles'] = $roles;
            $params['scopes']['ofRole'] = [ $roles ];
        }

        // Filter by name
        if( $names = Input::get('names', false) )
        {
            $params['names'] = $names;
            $params['scopes']['byName'] = [ $names ];
        }
        
        return $this->resources['user']->index($params);
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
        $object = $this->resources['user']->store($attributes);

        // Two-step activation
        if($twoStep)
        {
            $this->resources['user']->resetActivation($object->email, true);
        }

        return $object;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->resources['user']->show($id);
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
        $object = $this->resources['user']->update($id, $attributes);
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
        return $this->resources['user']->destroy($id, $force);
    }
    
}