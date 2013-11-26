<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
use Alba\Core\Controllers\Controller;
use \UsersResource;

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
        $params = Input::only('max', 'order', 'sort', 'keyword', 'start', 'end', 'role');
        $params['loadRelationships'] = ['roles'];

        // Filter by role
        /*if( isset($params['role']) )
        {
            $roles = $params['role'];
            
            // Convert roles string to array
            if ( is_string($roles) )
                $roles = explode(',', $roles);
            
            $params['where'] = function($query) use ($roles)
                {
                    $query->addSelect(['users.*', 'assigned_roles.role_id'])
                        ->join('assigned_roles', 'users.id', '=', 'assigned_roles.user_id')
                        ->whereIn('role_id', $roles);
                };
        }*/
        
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
        return $this->resources['user']->store($attributes);
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
     * @param array $attributes to fill on the object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id, $attributes)
    {
        $attributes = Input::all();
        return $this->resources['user']->store($attributes);
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