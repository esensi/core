<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

use Alba\Core\Controllers\Controller;
use Alba\Core\Utils\ProcessResponse;
use Alba\Core\Utils\ViewMessage;
use Alba\User\Models\User;
use Alba\User\Models\Name;
use Alba\User\Controllers\UsersResource;
use Alba\User\Controllers\UsersResourceException;
use Alba\User\Repositories\Contracts\UserRepositoryInterface;

/**
 * Controller for user interactions from a web interface
 *
 * @author diego <diego@emersonmedia.com>
 */
class UsersController extends Controller {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';

    /**
     * The resources injected
     * 
     * @var array
     */
    protected $resources;

    /**
     * The APIs injected
     * 
     * @var array
     */
    protected $apis;

    /**
     * Inject dependencies
     *
     * @todo make ViewMessage a dependency injection
     * @param UsersResource $user
     * @return void
     */
    public function __construct(UsersResource $user, UsersApiController $userApi)
    {
        $this->resources['user'] = $user;
        $this->apis['user'] = $userApi;
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $paginator = $this->apis['user']->index();
        $this->layout->content = View::make('alba::users.index', $paginator);
    }

    /**
     * Show the form for registering
     *
     * @return void
     */
    public function signup()
    {
        $this->layout->content = View::make('users.signup');
    }

    /**
     * Register the user then redirect to index
     *
     * @return Redirect
     */
    public function register()
    {
        // @todo create the user and handle default two-step activation
        return Redirect::route('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $this->layout->content = View::make('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $object = $this->apis['user']->store();

        return Redirect::route('users.show', ['id' => $object->id])
            ->with('message', Lang::get('alba::user.success.store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        $object = $this->resources['user']->show($id);        
        $this->layout->content = View::make('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        $object = $this->resources['user']->show($id);
        $this->layout->content = View::make('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        // @todo what about security here?

        $object = $this->apis['user']->update($id);

        return Redirect::route('users.show', ['id' => $id])
            ->with('message', Lang::get('alba::user.success.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function destroy($id)
    {
        // @todo what about security here?

        $this->apis['user']->destroy($id);

        return Redirect::route('users.index')
            ->with('message', Lang::get('alba::user.success.destroy'));
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
     * Process login attempt for a user
     *
     * @return Redirect
     */
    public function login()
    {
        // Log user in
        $credentials = Input::only(['email', 'password']);
        $extras = ['active' => true, 'blocked' => false];
        $remember = Input::get('remember', false);
        $object = $this->resources['user']->authenticate($credentials, $credentials, $remember);

        // If login is ok, redirect to the intended URL or default to the dashboard
        return Redirect::intended(route('index'))
            ->with('message', Lang::get('alba::user.success.login'));
    }

    /**
     * Log user out and redirect to sign in page
     *
     * @return Redirect
     */
    public function logout()
    {
        $this->resources['user']->unauthenticate();
        return Redirect::route('signin')
            ->with('message', Lang::get('alba::user.success.logout'));
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
        $object = $this->resources['user']->resetPassword($email);
        $this->layout->content = View::make('alba::users.reset-password')->with('user', $object);
    }

    /**
     * Show set new password page
     *
     * @param string $token
     * @return void
     */
    public function newPassword($token)
    {
        $this->layout->content = View::make('alba::users.new-password');
    }

    /**
     * Save user password and redirect user to profile page
     *
     * @return Redirect
     */
    public function setPassword()
    {
        $newPassword = Input::only('password', 'password_confirmation');
        $token = Input::get('token');
        $object = $this->resources['user']->setPassword($token, $newPassword);

        return Redirect::route('users.show', ['id' => $object->id])
            ->with('message', Lang::get('alba::user.success.set_password'));
    }

    /**
     * Show new activation request page
     *
     * @return void
     */
    public function newActivation()
    {
        $this->layout->content = View::make('alba::users.new-activation');
    }

    /**
     * Reset activation and show confirmation page
     * 
     * @param integer $id of User
     * @return void
     */
    public function resetActivation($id = null)
    {
        // Prefer to use $id over email for admins
        if ($id) // @todo restrict to admin privileges
        {
            $object = $this->resources['user']->show($id);
        }
        
        // Send activation email to user
        $email = ($id) ? $object->email : Input::get('email');
        $object = $this->resources['user']->resetActivation($email);

        // Show confirmation page
        $this->layout->content = View::make('alba::users.reset-activation')->with('user', $object);
    }

    /**
     * Activate the user that bears the token.
     * Optionally it saves a new password too.
     * 
     * @param string $token
     * @return Redirect
     */
    public function activate($token)
    {
        $newPassword = Input::has('password') ? Input::only('password', 'password_confirmation') : null;
        $object = $this->resources['user']->activate($token, $newPassword);

        return Redirect::route('users.show', ['id' => $id])
            ->with('message', Lang::get('alba::user.success.activate'));
    }

    /**
     * Deactivates the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function deactivate($id)
    {
        // @todo what about security here?

        $object = $this->resources['user']->deactivate($id);
        
        return Redirect::route('users.show', ['id' => $id])
            ->with('message', Lang::get('alba::user.success.deactivate'));
    }

    /**
     * Blocks the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function block($id)
    {
        // @todo what about security here?

        $object = $this->resources['user']->block($id);

        return Redirect::route('users.show', ['id' => $id])
            ->with('message', Lang::get('alba::user.success.block'));
    }

    /**
     * Unblocks the specified user 
     * 
     * @param integer $id
     * @return Redirect
     */
    public function unblock($id)
    {
        // @todo what about security here?

        $object = $this->resources['user']->unblock($id);
        
        return Redirect::route('users.show', ['id' => $id])
            ->with('message', Lang::get('alba::user.success.unblock'));
    }

}