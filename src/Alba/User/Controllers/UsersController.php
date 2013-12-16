<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

use Alba\Core\Controllers\Controller;
use Alba\User\Controllers\UsersResource;
use Alba\User\Controllers\UsersApiController;

/**
 * Controller for accessing UsersResrouce from a web interface
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Controllers\UsersResource
 * @see Alba\User\Controllers\UsersApiController
 */
class UsersController extends Controller {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'user';

    /**
     * Inject dependencies
     *
     * @param UsersResource $usersResource
     * @param UsersApiController $usersApi
     * @return void
     */
    public function __construct(UsersResource $usersResource, UsersApiController $usersApi)
    {   
        $this->resources['user'] = $usersResource;
        $this->apis['user'] = $usersApi;
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $paginator = $this->apis['user']->index();
        $collection = $paginator->getCollection();
        $this->content('index', compact('paginator', 'collection'));
    }

    /**
     * Show the search modal
     *
     * @return void
     */
    public function search()
    {
        // Get the models used
        $user = $this->resources['user']->getModel();
        $role = $this->resources['user']->getModel('role');

        // Get all the options
        $rolesOptions = $role->listAlphabetically();
        $activeOptions = $user->activeOptions;
        $blockedOptions = $user->blockedOptions;
        $orderOptions = $user->orderOptions;
        $trashedOptions = $user->trashedOptions;
        $sortOptions = $user->sortOptions;
        $maxOptions = $user->maxOptions;

        // Construct data arguments
        $options = compact('activeOptions', 'blockedOptions', 'trashedOptions', 'orderOptions', 'sortOptions', 'maxOptions', 'rolesOptions');
        $inputs = Input::only('order','sort','max','keywords','names','roles','active','blocked','trashed');
        $data = array_merge($options, $inputs);

        // Convert arrays used in text inputs to comma-separated values
        if(is_array($data['keywords']))
        {
            $data['keywords'] = implode(', ', $data['keywords']);
        }
        if(is_array($data['names']))
        {
            $data['names'] = implode(', ', $data['names']);
        }        

        // Pass data to search modal view
        $this->modal('search', $data);
    }

    /**
     * Show the form for registering
     *
     * @return void
     */
    public function signup()
    {
        $this->content('signup');
    }

    /**
     * Register the user then redirect to activation required page
     *
     * @return Redirect
     */
    public function register()
    {
        $object = $this->apis['user']->store();
        return $this->redirect('registered')
            ->with('message', $this->language('success.register'));
    }

    /**
     * Show registration and two-step activation confirmation page
     *
     * @return void
     */
    public function registered()
    {
        $this->content('registered');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $this->form('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $object = $this->apis['user']->store();

        return $this->redirect('store', ['id' => $object->id])
            ->with('message', $this->language('success.store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param boolean $withTrashed
     * @return void
     */
    public function show($id, $withTrashed = false)
    {
        $object = $this->resources['user']->show($id, $withTrashed);
        $this->content('show', ['user' => $object]);
    }

    /**
     * Display the currently authenticated resource.
     *
     * @return void
     */
    public function account()
    {
        $id = Auth::user()->id;
        $this->show($id);
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
        $this->form('edit', $object);
    }

    /**
     * Show the form for modifying the specified resource.
     *
     * @param string $view
     * @param  User $object
     * @return void
     */
    protected function form($view, $object = null)
    {        
        // Get all the options
        $titlesOptions = $this->resources['user']->titles('title');
        $suffixesOptions = $this->resources['user']->suffixes('suffix');
        $rolesOptions = $this->resources['user']->getModel('role')->listAlphabetically();
        $roles = isset($object) ? $object->roles->lists('id') : [];

        // Parse view data
        $data = compact('titlesOptions', 'suffixesOptions', 'rolesOptions', 'roles');
        if(!is_null($object))
        {
            $data['user'] = $object;
        }
        $this->content($view, $data);
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

        return $this->redirectBack('update', ['id' => $id])
            ->with('message', $this->language('success.update'));
    }

    /**
     * Show sign in page
     *
     * @return void
     */
    public function signin()
    {
        $this->content('signin');
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
        $object = $this->resources['user']->authenticate($credentials, $extras, $remember);

        // If login is ok, redirect to the intended URL or default to the dashboard
        $route = Config::get('alba::user.redirects.login');
        return Redirect::intended(route($route))
            ->with('message', $this->language('success.login'));
    }

    /**
     * Log user out and redirect to sign in page
     *
     * @return Redirect
     */
    public function logout()
    {
        $this->resources['user']->unauthenticate();
        return $this->redirect('logout')
            ->with('message', $this->language('success.logout'));
    }

    /**
     * Show forgot password page
     *
     * @return void
     */
    public function forgotPassword()
    {
        $this->content('forgot_password');
    }

    /**
     * Send user password reset URL and show confirmation page or redirect to forgot password page with errors
     *
     * @param integer $id of User
     * @return void
     */
    public function resetPassword($id = null)
    {
        // Prefer to use $id over email for admins
        if ($id) // @todo restrict to admin privileges
        {
            $object = $this->resources['user']->show($id);
        }
        
        // Send activation email to user
        $email = isset($object->email) ? $object->email : Input::get('email');
        $object = $this->resources['user']->resetPassword($email);
        $this->content('reset_password', ['users' => $object]);
    }

    /**
     * Show set new password page
     *
     * @param string $token from resetPassword
     * @return void
     */
    public function newPassword($token)
    {
        $this->content('new_password', ['token' => $token]);
    }

    /**
     * Save user password and redirect user to profile page
     *
     * @param string $token from newPassword
     * @return Redirect
     */
    public function setPassword($token)
    {
        $newPassword = Input::only('password', 'password_confirmation');
        $object = $this->resources['user']->setPassword($token, $newPassword);

        // Authenticate the user if not already logged in
        if ( Auth::guest() )
        {
            Auth::login($object);
        }

        return $this->redirect('set_password')
            ->with('message', $this->language('success.set_password'));
    }

    /**
     * Show new activation request page
     *
     * @return void
     */
    public function newActivation()
    {
        $this->content('new_activation');
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
        $this->content('reset_activation', ['user' => $object]);
    }

    /**
     * Show set password page for reset activation flow
     *
     * @param string $token from resetActivation
     * @return void
     */
    public function activatePassword($token)
    {
        $this->content('activate_password', ['token' => $token]);
    }

    /**
     * Activate the user that bears the token.
     * Optionally it saves a new password too.
     * 
     * @param string $token from activate
     * @return Redirect
     */
    public function activate($token)
    {
        // Activate the user with optional password
        $newPassword = Input::has('password') ? Input::only('password', 'password_confirmation') : null;
        $object = $this->resources['user']->activateWithToken($token, $newPassword);

        // Authenticate the user if not already logged in
        if ( Auth::guest() )
        {
            Auth::login($object);
            
            // Redirect to user profile
            return $this->redirect('activate.guest')
                ->with('message', $this->language('success.activate'));
        }

        // Redirect to user profile
        return $this->redirect('activate.user', ['id' => $object->id])
            ->with('message', $this->language('success.activate'));
    }

}