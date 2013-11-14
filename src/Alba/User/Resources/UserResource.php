<?php namespace Alba\User\Resources;

use Auth;
use Input;
use Lang;
use Session;
use Role;
use User;
use Alba\Core\CoreResource;
use Alba\User\Contracts\UserResourceInterface;

class UserResource extends CoreResource implements UserResourceInterface{

	/**
	 * The role IDs to search within
	 *
	 * @var array
	 */
	public $role = [];

	/**
	 * Display a listing of the resource.
	 *
	 * @param array $params to overload
	 * @return Collection
	 */
	public function index($params = [])
	{
		// Restrict to authenticated users
		if( Auth::guest() )
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.auth.error')
			];
		}
		
		// Setup object query
		$this->defaults['role'] = [];
		$this->overload($params);
		$this->keyword(User::$searchable);
		
		// Get the object
		$object = User::with(['roles']);

		// For admins include trashed items
		if(Auth::check() && Auth::user()->hasRole('admin'))
			$object = $object->withTrashed();
		
		// Filter by role
		if($this->role != $this->defaults['role'])
		{
			$object->addSelect(['users.*', 'role_id'])
				->join('assigned_roles', 'users.id', '=', 'assigned_roles.user_id')
				->whereIn('role_id', $this->role);
		}

		// Filter by start date
		if($this->start != $this->defaults['start'])
		{
			$object->where('updated_at', '>=', date('Y-m-d', strtotime($this->start)));
		}

		// Filter by end date
		if($this->end != $this->defaults['end'])
		{
			$object->where('updated_at', '<=', date('Y-m-d', strtotime($this->end) + (3600 * 24) ));
		}

		// Paginate the results
		$this->paginator = $object->where($this->where)
			->orderBy($this->order, $this->sort)
			->paginate($this->max);
		
		return $this->paginator->getCollection();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return array
	 */
	public function store()
	{
		// Create new object
		$role = Input::get('role');
		$data = Input::only(['first_name', 'last_name', 'email', 'password']);
		$obj = new User;
		$obj->fill($data);
		if(isset($role))
			$obj->role = $role;

		// Show auth error when user is already authenticated (not an admin)
		// or is not registering as a type of user
		if( ( Auth::check() && !Auth::user()->hasRole('admin') ) ||
			( Auth::guest() && !in_array($role, ['member','staff']) ) )
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.auth.error')
				];
		}

		// Show validation errors
		$rules = User::$rules;
		$rules['role'][] = 'required';
		if($obj->validate($rules) == false)
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.save.error'),
				'errors' => $obj->errors()->all()
				];
		}
		if(isset($obj->role))
			unset($obj->role);
		$obj->save();

		// Attach the role to the object
		$role = Role::whereName($role)->first();
		$obj->attachRole($role);

		// @todo: Send email to new user
		// @todo: Send notification to admin users
		
		// Log guests in as new user
		if(Auth::guest())
		{
			Auth::login($obj);
		}

		// Show success
		return [
			'success' => true,
			'message' => Lang::get('api.user.save.success'),
			'object' => $obj->toArray()
			];
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return User
	 */
	public function show($id)
	{
		// Get object
		$obj = ( Auth::check() && Auth::user()->hasRole('admin') ) ? User::withTrashed() : new User;
		$obj = $obj->with(['roles'])
			->find($id);

		// Show missing object error
		if(empty($obj))
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.show.error')
				];
		}

		// Show auth error for non-owner users who are not admins
		if( ( Auth::guest() ) ||
			( Auth::check() && !Auth::user()->hasRole('admin') && Auth::user()->id != $obj->id ) )
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.auth.error')
				];
		}

		return $obj;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return array
	 */
	public function update($id)
	{
		// Get existing object
		$role = Input::get('role');
		$obj = $this->show($id);
		if(!$obj instanceof User)
			return $obj;

		// Show auth error if user is not an admin and is trying to change roles
		if( !Auth::user()->hasRole('admin') && !empty($role) )
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.auth.error')
				];
		}

		// Show validation errors
		$rules = array_shift($obj::$rules['role']);
		if($obj->save($rules) == false)
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.save.error'),
				'errors' => $obj->errors()->all()
				];
		}

		// Let admin update role
		if( Auth::user()->hasRole('admin') && !empty($role) )
		{
			// Attach the role to the model
			$role = Role::whereName($role)->first();
			$obj->roles()->sync($role->id);
		}
		
		// Show success
		return [
			'success' => true,
			'message' => Lang::get('api.user.save.success'),
			'object' => $obj->toArray()
			];
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return array
	 */
	public function destroy($id)
	{
		// Get existing object
		$obj = $this->show($id);
		if(!$obj instanceof User)
			return $obj;

		// Let admin delete object
		if(Auth::user()->hasRole('admin'))
		{
			$obj->forceDelete();
		}

		// Otherwise soft delete object
		else
		{
			$obj->delete();
		}

		return [
			'success' => true,
			'message' => Lang::get('api.user.delete.success')
			];
	}

	/**
	 * Log the user in.
	 *
	 * @return array
	 */
	public function login()
	{
		$data = Input::only(['email', 'password']);
		
		// Do login and show any errors
		if( !Auth::attempt($data) )
		{
			return [
				'success' => false,
				'message' => Lang::get('api.user.login.error')
				];
		}

		return [
			'success' => true,
			'message' => Lang::get('api.user.login.success')
			];
	}

	/**
	 * Log the user out.
	 *
	 * @return array
	 */
	public function logout()
	{
		Session::flush();
		return [
			'success' => true,
			'message' => Lang::get('api.user.logout.success')
			];
	}

}