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
    protected $layout = 'layouts.default';

    /**
     * The resource controllers
     *
     * @var array;
     */
    protected $resources = [];

    /**
     * Inject dependencies
     * @todo make ViewMessage a dependency injection
     * @param UserRepositoryInterface $userRepo
     * @return void
     */
    public function __construct(UsersResource $usersResource)
    {
        $this->resources['user'] = $usersResource;
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index() {

        $params = Input::only('max', 'order', 'sort', 'keyword');
        $paginator = $this->resources['user']->index();

        $this->layout->content = View::make('users.index', $paginator);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create() {
        $this->layout->content = View::make('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store() {
        
        $rules = array_merge($this->resources['user']->getModel()->rulesForStoring, $this->resources['user']->getModel('name')->rulesForStoring);
        $attributes = Input::only(array_keys($rules));
        $user = $this->resources['user']->store($attributes);

        $type = ViewMessage::SUCCESS;
        $message = 'The user was successfully created!';

        // Send activation email
        if (Input::get('send_email') == 'true')
        {
            
            $args = Input::all(); // @todo we should limit this to what is actualy needed
            $resp = $this->processRequestActivation($args);
            if ($resp->isSuccess())
            {
                // @todo remove the debug info
                // @todo move to language file
                $message = 'The user was successfully created, and the activation email was sent! ' .
                    'DEBUG = <pre>' . print_r($resp->getHolder(), true) . '</pre>';
            }
            else
            {
                $type = ViewMessage::WARNING;
                $message = 'The user was successfully created, BUT the activation email couldn\'t be sent.
                    Please try to send it again from the users details page.';
            }

        }

        return Redirect::route('users.index')->with('message', new ViewMessage($type, $message)); // @todo make ViewMessage a dependency injection

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show($id) {

        $user = $this->resources['user']->show($id);        
        $this->layout->content = View::make('users.show', compact('user'));
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id) {
        
        $user = $this->resources['user']->show($id);
        $this->layout->content = View::make('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id) {

        // @todo what about security here?

        $attributes = Input::all(); // @todo this should only pass what's strictly needed using Input::only()
        $user = $this->resources['user']->update($id, $attributes);

        return Redirect::route('users.show', ['id' => $id])->with('message', 
                new ViewMessage(ViewMessage::SUCCESS, 'User successfully saved!') // @todo make ViewMessage a dependency injection, move to language file
            );
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // @todo what about security here?
    }

    /**
     * Process login attempt for a user
     *
     * @return Redirect
     */
    public function login() {

        $model = $this->resources['user']->getModel();
        $attributes = Input::only(array_keys($model->rulesForLogin));
        $user = $this->resources['user']->login($attributes);

        // If login is ok, redirect to the intended URL or default to the dashboard
        return Redirect::intended(route('index'));

    }

    /**
     * Logs the user out of the application and redirects to the home page
     *
     * @return Redirect
     */
    public function logout() {
        
        Auth::logout();
        return Redirect::route('index');
    }

    /**
     * Blocks the specified user 
     * 
     * @param  integer $id User id to block
     * @return Response
     */
    public function block($id) {

        // @todo what about security here?

        // Find the user
        $user = $this->userResource->show($id);

        // Block the user
        // @todo add conditional error handling
        $user->block();
        
        return Redirect::route('users.show', ['id' => $id])->with('message', 
                new ViewMessage(ViewMessage::WARNING, 'User blocked!') // @todo make ViewMessage a dependency injection, move to language file
            );
    }

    /**
     * Unblocks the specified user 
     * 
     * @param  integer $id User id to unblock
     * @return Redirect
     */
    public function unblock($id) {

        // @todo what about security here?

        // Find the user
        $user = $this->userResource->show($id);

        // Unblock the user
        // @todo add conditional error handling
        $user->unblock();
        
        return Redirect::route('users.show', ['id' => $id])->with('message', 
                new ViewMessage(ViewMessage::SUCCESS, 'User unblocked!') // @todo make ViewMessage a dependency injection, move to language file
            );
    }

    /**
     * Deactivates the specified user 
     * 
     * @param  integer $id User id to deactivate
     * @return Response
     */
    public function deactivate($id) {

        // @todo what about security here?

        // Find the user
        $user = $this->userResource->show($id);
        
        // Deactivate the user
        // @todo add conditional error handling
        $user->deactivate();
        
        return Redirect::route('users.show', ['id' => $id])->with('message', 
                new ViewMessage(ViewMessage::WARNING, 'User deactivated!') // @todo make ViewMessage a dependency injection, move to language file
            );
    }

    /**
     * Shows the register page for user with deactivated accounts
     *
     * @return void
     */
    public function requestActivationInit() {
        $this->layout->content = View::make('users.requestActivationInit');
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
    private function processRequestActivation($inputData) {

        $user = $this->resources['user']->requestActivation($inputData);

        //if all is ok, get the activation token and send email...
        $token = $user->getActivationToken();

        //generate activation URL
        $activationUrl = route('users.requestActivationPassword', ['token' => $token->token]);

        //current date
        $now = new Carbon();

        $senderEmail = Config::get('app.activationEmailSenderAddress', 'info@dorot.org');
        $senderName = Config::get('app.activationEmailSenderName', 'Dorot Foundation');

        $data = [
            'activationUrl' => $activationUrl,
            'currentYear' => $now->year,
            'dorotLogoUrl' => asset('img/logo.png'),
            'contactUsEmail' => $senderEmail, 
            'userName' => $user->getFullName('F') // @todo replace with accessor such as $user->first_name or just use $user->name->first_name
        ];

        //send email
        // @todo send email as both html and plain text
        Mail::send('emails.html.activateAccount', $data, 
            function($message) use ($user, $senderEmail, $senderName) {
            // @todo add the email and name from to a config parameter.
            $message->to($user->email, $user->getFullName('F L'))
                ->from($senderEmail, $senderName)
                ->subject('Activate Your Account') // @todo move to language file
            ;
        });

        // @todo remove the data pased to the view, just done for debugging...        
        return new ProcessResponse(true, null, $data);

    }

    /**
     * Performs the processing of the activation request from the admin flow
     * 
     * @return Redirect
     */
    public function requestActivationAdmin($id) {

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
    public function requestActivation() {

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
     * @return Response
     */
    public function requestActivationPassword($token) {

        //get user by token
        try
        {
            $user  = $this->resources['user']->showByActivationToken($token);
        }
        catch (UsersResourceException $e)
        {
            return View::make('users.requestActivationPasswordBadToken');
        }

        //show page to generate password
        $this->layout->content = View::make('users.requestActivationPassword',compact('user', 'token'));

    }

    /**
     * Search the user account using the token, validate the password.
     * If everything is ok, set password and activate account
     * 
     * @param  string $token [description]
     * @return Redirect, View, or void
     */
    public function activate($token) {

        $attributes = array_merge(
                array('token' => $token),
                Input::all()
            );
        $user = $this->resources['user']->activate($attributes);

        //@todo: This should be a redirect, to avoid possible double submitions
        //account activated!
        $this->layout->content = View::make('users.activate');

    }

    /**
     * Shows the page for requesting the password reset for the user flow
     * @return Response
     */
    public function requestPasswordResetInit() {
        $this->layout->content = View::make('users.requestPasswordResetInit');
    }

    /**
     * Process the password reset request, validating the email, generating
     * a new password request token and sending the email to the user.
     * It returns the result of the operation with a ProcessResponse. When 
     * in error, it sets the holder to indicate the ViewMessage type to
     * use when reporting back to the view. When Success, it set the 
     * holder to the data used in email view
     * 
     * @param  array $attrributes Array with input data, must have 'email' key
     * @return ProcessResponse
     */
    private function processRequestPasswordReset($attributes) {

        $user = $this->resources['user']->requestPasswordReset($attributes);

        //if all is ok, get the passwordReset token and send email...
        $token = $user->getPasswordResetToken();

        //generate password reset URL
        $passwordResetUrl = route('users.passwordResetInit', ['token' => $token->token]);

        //current date
        $now = new Carbon();

        $senderEmail = Config::get('app.passResetEmailSenderAddress', 'info@dorot.org');
        $senderName = Config::get('app.passResetEmailSenderName', 'Dorot Foundation');

        $data = [
            'userName' => $user->getFullName('F'),
            'passwordResetUrl' => $passwordResetUrl,
            'currentYear' => $now->year,
            'dorotLogoUrl' => asset('img/logo.png'),
            'contactUsEmail' => $senderEmail,
        ];

        //send email
        Mail::send('emails.html.passwordReset', $data, 
            function($message) use ($user, $senderEmail, $senderName) {
            $message->to($user->email, $user->getFullName('F L'))
                ->from($senderEmail, $senderName)
                ->subject('Reset Your Password')
            ;
        });

        // @todo remove the data pased to the view, just done for debugging...        
        return new ProcessResponse(true, null, $data);

    }

    /**
     * Tries to reset the users password if everything is correct, for the user flow
     * 
     * @return Response
     */
    public function requestPasswordReset() {

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
    public function requestPasswordResetAdmin($id) {

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
    public function passwordResetInit($token) {

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
    public function passwordReset($token) {

        $attributes = array_merge(
                array('token' => $token),
                Input::all()
            );
        $user = $this->resources['user']->resetPassword($attributes);

        //@todo: This should be a redirect, to avoid possible double submitions        
        $this->layout->content = View::make('users.passwordReset');
        
    }

}