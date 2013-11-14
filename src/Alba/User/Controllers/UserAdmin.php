<?php namespace Alba\User\Controllers;

use Redirect;
use Role;
use View;
use Illuminate\Support\Collection;
use Alba\Core\CoreController;
use Alba\User\Contracts\UserResourceInterface;

class UserAdmin extends CoreController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'admin.layouts.default';

	/**
     * The API responsible for user interactions
     */
    protected $users;

	/**
	 * Inject dependencies
	 *
	 * @param ApiInterface $users
	 * @return void
	 */
	public function __construct(UserResourceInterface $users)
	{
		$this->users = $users;
	}

	/**
	 * Display resource list
	 *
	 * @return Response
	 */
	public function index()
	{
		// Query API
		$response = $this->users->index();
		
		// API returned collection
		if($response instanceof Collection)
		{
			$columns = $this->users->makeColumns([
				'id' => 'ID',
				'first_name' => 'First Name',
				'last_name' => 'Last Name',
				'email' => 'Email',
				'Role' => false,
				]);
			$view = View::make('admin.user.index')
				->with('roles', Role::all())
				->with('columns', $columns)
				->with('collection', $response)
				->with('paginator', $this->users->paginator);

			$this->layout->paginator = $this->users->paginator;
			$this->layout->content = $view;
		}

		// API returned error
		else
		{
			Redirect::route('error')->with($response);
		}
	}

	/**
	 * Display create modal
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->layout = 'admin.layouts.modal';
		$this->layout->content = View::make('admin.user.form');
	}

	/**
	 * Display edit modal
	 *
	 * @return Response
	 */
	public function edit()
	{
		$this->layout = 'admin.layouts.modal';
		$this->layout->content = View::make('admin.user.form');
	}

	/**
	 * Display search modal
	 *
	 * @return Response
	 */
	public function search()
	{
		$this->layout = 'admin.layouts.modal';
		$this->layout->content = View::make('admin.user.search');
	}
}