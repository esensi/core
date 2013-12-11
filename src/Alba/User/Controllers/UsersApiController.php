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
        $params = Input::only('max', 'order', 'sort', 'keywords', 'trashed');

        // Join the names table when needed
        if(in_array($params['order'], ['name', 'first_name', 'last_name']))
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
        if( $roles = Input::get('roles', false) )
        {
            $roles = is_array($roles) ? $roles : explode(',', $roles);
            $roles = array_values($roles);
            $test = implode('', $roles);
            if(!empty($test))
            {
                $params['roles'] = $roles;
                $params['scopes']['ofRole'] = [ $roles ];
            }
        }

        // Filter by name
        if( $names = Input::get('names', false) )
        {
            $names = is_array($names) ? $names : explode(',', $names);
            $names = array_values($names);
            $test = implode('', $names);
            if(!empty($test))
            {
                $params['names'] = $names;
                $params['scopes']['byName'] = [ $names ];
            }
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
     * @param boolean $withTrashed
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id, $withTrashed = false)
    {
        return $this->resources['user']->show($id, $withTrashed);
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

    /**
     * Restore the specified resource from soft delete.
     *
     * @param int $id of object to remove
     * @return Illuminate\Database\Eloquent\Model
     * 
     */
    public function restore($id)
    {
        return $this->resources['user']->restore($id);
    }

    /**
     * Display a list of all available titles
     *
     * @return array
     */
    public function titles()
    {
        return $this->resources['user']->titles();
    }

    /**
     * Display a list of all available suffixes
     *
     * @return array
     */
    public function suffixes()
    {
        return $this->resources['user']->suffixes();
    }

}