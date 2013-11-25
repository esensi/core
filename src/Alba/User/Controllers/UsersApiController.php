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

}