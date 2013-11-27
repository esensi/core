<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
use Alba\Core\Controllers\Controller;
use Alba\User\Controllers\UsersResource;

class UsersApiController extends Controller {

	/**
     * The resource injected
     * 
     * @var array;
     */
    protected $resources;

    /**
     * Inject dependencies
     *
     * @param Alba\User\Controllers\UsersResource $user;
     * @return void
     */
	public function __construct(UsersResource $user)
	{
		$this->resources['user'] = $user;
	}

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $params = Input::only('max', 'order', 'sort', 'keyword');

        // Filter by role
        if( $role = Input::get('roles', false) )
        {
            $params['scopes']['ofRole'] = [ $role ];
        }

        // Filter by name
        if( $name = Input::get('names', false) )
        {
            $params['scopes']['byName'] = [ $name ];
        }
        
        return $this->resources['user']->index($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $attributes = Input::all();
        $object = $this->resources['user']->store($attributes);

        // Two-step activation
        $this->resources['user']->resetActivation($object->email, true);

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