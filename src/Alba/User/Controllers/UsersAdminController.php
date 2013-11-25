<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Alba\Core\Controllers\Controller;
use Alba\User\Controllers\UsersResource;

class UsersAdminController extends Controller {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';

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
     * Show sign in page
     *
     * @return void
     */
	public function signin()
	{
		$this->layout->content = View::make('alba::users.signin');
	}

	/**
     * Log user in or redirect to sign in page with errors
     *
     * @return Redirect
     */
	public function login()
	{
		// Log user in
		$credentials = Input::only('email', 'password');
		$extras = ['active' => true, 'blocked' => false];
		$remember = Input::get('remember', false);
		$this->resources['user']->login($credentials, $extras, $remember);
		
		// Redirect to intended URL
		$intended = Input::get('intended', route('dashboard'));
		return Redirect::to($intended);
	}

	/**
     * Log user out and redirect to sign in page
     *
     * @return Redirect
     */
	public function logout()
	{
		$this->resources['user']->logout();
		return Redirect::route('users.signin');
	}

	/**
     * Show forgot password page
     *
     * @return void
     */
	public function forgotPassword()
	{
		$this->layout->content = View::make('alba::users.forgot-password');
	}

	/**
     * Send user password reset URL and show confirmation page or redirect to forgot password page with errors
     *
     * @return void
     */
	public function resetPassword()
	{
		$email = Input::get('email');
		$user = $this->resources['user']->resetPassword($email);
		$this->layout->content = View::make('alba::users.reset-password')->with('user', $user);
	}

	/**
     * Show set password page
     *
     * @return void
     */
	public function setPassword($token)
	{
		$this->layout->content = View::make('alba::users.set-password');
	}

	/**
     * Save user password and redirect user to index page
     *
     * @return Redirect
     */
	public function savePassword()
	{
		return Redirect::route('admin.users.index');
	}
}