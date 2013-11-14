<?php namespace Alba\User\Controllers;

use Redirect;
use View;
use Alba\Core\CoreController;
use Alba\User\Contracts\UserResourceInterface;

class UserController extends CoreController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.default';

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
	 * Display user password modal
	 *
	 * @return Response
	 */
	public function changePassword()
	{
		$this->layout->content = View::make('user.change-password');
	}

	/**
	 * Display user login modal
	 *
	 * @return Response
	 */
	public function login()
	{
		$this->layout->content = View::make('user.login');
	}

	/**
	 * Display user logout
	 *
	 * @return Response
	 */
	public function logout()
	{
		$this->users->logout();
		return Redirect::route('index');
	}

	/**
	 * Display user profile modal
	 *
	 * @return Response
	 */
	public function profile()
	{
		$this->layout->content = View::make('user.profile');
	}

	/**
	 * Display user registration modal
	 *
	 * @return Response
	 */
	public function register()
	{
		$this->layout->content = View::make('user.register');
	}

	/**
	 * Display user password reset modal
	 *
	 * @return Response
	 */
	public function resetPassword()
	{
		$this->layout->content = View::make('user.reset-password');
	}
}