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
        // Save the user
        $attributes = Input::all();
        $user = $this->resources['user']->store($attributes);

        // Two-step activation
        $this->resources['user']->resetActivation($user->email, true);

        return Redirect::route('users.show', ['id' => $user->id])
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
        $user = $this->resources['user']->show($id);        
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
        $user = $this->resources['user']->show($id);
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

        $attributes = Input::all();
        $user = $this->resources['user']->update($id, $attributes);

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
        $force = Input::get('force');
        $this->resources['user']->destroy($id, $force);

        // @todo add flash success message
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
        $user = $this->resources['user']->authenticate($credentials, $credentials, $remember);

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
        $user = $this->resources['user']->resetPassword($email);
        $this->layout->content = View::make('alba::users.reset-password')->with('user', $user);
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
     * Save user password and redirect user to index page
     *
     * @return Redirect
     */
    public function setPassword()
    {
        $newPassword = Input::only('password', 'password_confirmation');
        $token = Input::get('token');
        $user = $this->resources['user']->setPassword($token, $newPassword);

        return Redirect::route('index')
            ->with('message', Lang::get('alba::user.success.set_password'));
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

        $user = $this->resources['user']->block($id);

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

        $user = $this->resources['user']->unblock($id);
        
        return Redirect::route('users.show', ['id' => $id])
            ->with('message', Lang::get('alba::user.success.unblock'));
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
        $user = $this->resources['user']->activate($token, $newPassword);

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

        $user = $this->resources['user']->deactivate($id);
        
        return Redirect::route('users.show', ['id' => $id])
            ->with('message', Lang::get('alba::user.success.deactivate'));
    }

    /**
     * Checks the user supplied email to see if corresponds to an
     * account that can be activated... if that's the case, it generates
     * a new activation token account and sends the activation email
     * to the user. 
     * It returns the result of the operation with a ProcessResponse. When 
     * in error, it sets the holder to indicate a 'danger' or 'warning' 
     * type of alert. When Success, it set the holder to the data used in
     * email view
     *
     * @param array $inputData Data containing the email of user to rquest activation
     * 
     * @return ProcessResponse Result of operation
     */
    private function processRequestActivation($inputData)
    {
        // Get the user and token
        $user = $this->resources['user']->resetActivation($inputData['email']);

        // @todo remove the data pased to the view, just done for debugging... 
        return new ProcessResponse(true, null, $data);
    }

    /**
     * Performs the processing of the activation request from the admin flow
     * 
     * @return Redirect
     */
    public function requestActivationAdmin($id)
    {
        $input = Input::all(); // @todo this should only pass what's strictly needed using Input::only()
        
        //now if the method executes without exception, it will always be success
        $resp = $this->processRequestActivation($input);

        // @todo remove the passing of data into the message... just for debugging
        return Redirect::route('users.show', ['id' => $id])->with('message',
                new ViewMessage(ViewMessage::SUCCESS, 'Activation email sent! Debug data = <pre>' . print_r($resp->getHolder(), true) . '</pre>') // @todo make ViewMessage a dependency injection, move to language file
            );
    }

    /**
     * Performs the processing of the activation request from the user flow
     * 
     * @return Redirect or void
     */
    public function requestActivation()
    {
        // @todo don't return a mixed response such as Redirect or void
        $input = Input::all(); // @todo this should only pass what's strictly needed using Input::only()
        
        //now if the method executes without exception, it will always be success
        $resp = $this->processRequestActivation($input);

        // @todo remove the data pased to the view, just done for debugging... 
        
        // @todo this could end up getting a double submission since it isn't redirected
        $this->layout->content = View::make('users.requestActivation')->with($resp->getHolder());
    }

    /**
     * Searches the user account using the activation token.
     * If corresponds to a valid activation, it shows page 
     * for the user to create the password
     * 
     * @param  string $token [description]
     * @return void
     */
    public function requestActivationPassword($token)
    {
        try
        {
            $user  = $this->resources['user']->showByActivationToken($token);
            $this->layout->content = View::make('users.requestActivationPassword',compact('user', 'token'));
        }
        catch (UsersResourceException $e)
        {
            $this->layout->contet = View::make('users.requestActivationPasswordBadToken');
        }
    }

    /**
     * Process the password reset request, validating the email, generating
     * a new password request token, and sending the email to the user.
     * It returns the result of the operation with a ProcessResponse. When 
     * in error, it sets the holder to indicate the ViewMessage type to
     * use when reporting back to the view. When Success, it set the 
     * holder to the data used in email view
     * 
     * @param  array $attrributes Array with input data, must have 'email' key
     * @return ProcessResponse
     */
    private function processRequestPasswordReset($attributes)
    {
        // Generate a new user password token and send user a password reset email
        $user = $this->resources['user']->resetPassword($attributes['email']);

        // @todo remove the data pased to the view, just done for debugging...        
        return new ProcessResponse(true, null, $data);

    }

    /**
     * Tries to reset the users password if everything is correct, for the user flow
     * 
     * @return Response
     */
    public function requestPasswordReset()
    {
        $attributes = Input::all();
        $resp = $this->processRequestPasswordReset($attributes);

        if ($resp->isFailure())
        {
            return Redirect::route('users.requestPasswordResetInit')->withInput()->with(
                'message', new ViewMessage($resp->getHolder(), $resp->getMessage()));
        }

        // @todo remove the data from the view, is just for debgging:
        $this->layout->content = View::make('users.requestPasswordReset', $resp->getHolder());
    }

    /**
     * Tries to initiate the user password reset process, from the admin flow
     * 
     * @param integer $id
     * @return Redirect
     */
    public function requestPasswordResetAdmin($id)
    {
        $attributes = Input::all();
        $resp = $this->processRequestPasswordReset($attributes);

        if ($resp->isFailure())
        {
            return Redirect::route('users.requestPasswordResetInit')->withInput()->with(
                'message', new ViewMessage($resp->getHolder(), $resp->getMessage()));
        }

        // @todo remove the passing of the holder to the message in the view, just for debugging
        return Redirect::route('users.show', $id)->with('message', new ViewMessage(
            ViewMessage::SUCCESS,
            'The reset password email was sent successfully. DEBUG = ' . print_r($resp->getHolder(), true)
            ));
    }

    /**
     * Gets the token and tries to start the passwordReset flow for
     * the user
     * 
     * @param string $token 
     * @return void
     */
    public function passwordResetInit($token)
    {
        try 
        {
            $user = $this->resources['user']->showByPasswordResetToken($token);
        } 
        catch (UsersResourceException $e)
        {
            return View::make('users.requestPasswordResetBadToken');
        }

        $data = [
            'token' => $token,
        ];

        $this->layout->content = View::make('users.passwordResetInit', $data);
    }

    /**
     * Receives a post with all the info for performing a password reset for
     * a user, from the user flow.
     * 
     * @param string $token
     * @return void
     */
    public function passwordReset($token)
    {
        $attributes = array_merge(
                array('token' => $token),
                Input::all()
            );
        $user = $this->resources['user']->resetPassword($attributes);

        //@todo: This should be a redirect, to avoid possible double submitions        
        $this->layout->content = View::make('users.passwordReset');
    }

}