<?php

namespace Alba\User\Controllers;

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

use Alba\Core\Controllers\CoreController;
use Alba\Core\Utils\ProcessResponse;
use Alba\Core\Utils\ViewMessage;
use Alba\User\Models\User;
use Alba\User\Models\Name;
use Alba\User\Controllers\TokensResource;
use Alba\User\Controllers\UsersResource;
use Alba\User\Controllers\UsersResourceException;
use Alba\User\Repositories\Contracts\UserRepositoryInterface;

/**
 * Controller for user interactions from a web interface
 *
 * @author diego <diego@emersonmedia.com>
 */
class UsersController extends CoreController {

    
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.default';

    /**
     * The UsersResource controller
     * @var Alba\User\Controllers\UsersResource;
     */
    protected $usersResource;

    
    /**
     * The repository used for user interactions
     */
    protected $userRepo;

    /**
     * The TokensResource controller, used to handle tokens
     * @var Alba\User\Controllers\TokensResource;
     */
    protected $tokensResource;

    
    /**
     * Inject dependencies
     *
     * @param UserRepositoryInterface $userRepo
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $userRepo, 
        UsersResource $usersResource,
        TokensResource $tokensResource)
    {
        // @todo make ViewMessage a dependency injection
        $this->userRepo = $userRepo;
        $this->usersResource = $usersResource;
        $this->tokensResource = $tokensResource;
    }


    /**
     * Process login attempt for a user
     * @return Redirect
     */
    public function login() {

        // Get the rules for validating the form data
        // @todo move to User model
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        // Get the account inputs needed for authentication
        $account = Input::only(array_keys($rules));
        $validator = Validator::make($account, $rules);
        if ($validator->fails())
        {
            return Redirect::route('index')
                ->with('message', new ViewMessage(ViewMessage::DANGER, $validator->messages())); // @todo make ViewMessage a dependency injection
        }

        // Validate the credentials with a fake attempt
        if (!Auth::validate($account))
        {
            return Redirect::route('index')->with('message', 
                new ViewMessage(ViewMessage::DANGER, 'Your email and/or password are incorrect!') // @todo move to a language file
            );
        }
        
        // User will need to be active and not blocked
        $credentials = array_merge($account, ['active' => true, 'blocked' => false]);

        // Check the credentials with a real login
        if(!Auth::attempt($credentials))
        {
            $message = Auth::getProvider()->retrieveByCredentials($account)->getLoginAllowedMessage(); // @todo find another way to compute the messages or show a more generic one
            return Redirect::route('index')->with('message',
                new ViewMessage(ViewMessage::DANGER, $message) // @todo make ViewMessage a dependency injection
            );
        }

        // Log authentication
        // @todo this should be bound as a listener on auth.login
        Auth::user()->doLoginActions();
        
        // Redirect to the intended URL or default to the dashboard
        return Redirect::intended(route('dashboard.index'));
    }



    /**
     * Logs the user out of the application and redirects to the home page
     * @return Redirect
     */
    public function logout() {
        Auth::logout();
        return Redirect::route('index');
    }


    
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index() {

        $users = $this->userRepo->all(); // @todo this should be paginated and the paginator returned to the view too

        $data = ['users' => $users];
        $this->layout->content = View::make('users.index', $data);

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
           
        $input = Input::all(); // @todo this should only pass what's strictly needed using Input::only()
        $repoResponse = $this->userRepo->store($input);

        if ($repoResponse->isFailure()) {
            return Redirect::route('users.create')->withInput()->with('message', 
                new ViewMessage(ViewMessage::DANGER, $repoResponse->getMessage()) // @todo make ViewMessage a dependency injection
            );
        }

        // send activation email?
        // @todo don't use mixed case variables when dealing with HTML but rather snake_cased
        if ($input['sendEmail'] == 'true') {
            
            $resp = $this->processRequestActivation($input);
            if ($resp->isSuccess()) {
                $type = ViewMessage::SUCCESS;
                // @todo remove the debug info
                $message = 'The user was successfully created, and the activation email was sent! ' .
                    'DEBUG = <pre>' . print_r($resp->getHolder(), true) . '</pre>';
            } else {
                $type = ViewMessage::WARNING;
                $message = 'The user was successfully created, BUT the activation email couldn\'t be sent. Please try to send it again from the users details page.';
            }

        } else {
            $type = ViewMessage::SUCCESS;
            $message = 'The user was successfully created!';
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

        // Find the user
        // @todo Using the find method should throw a ModelNotFoundException which should be caught to show a proper 404 page
        $user = $this->userRepo->find($id);
        if ($user === null)
        {
            App::abort(404, 'User not found!');
        }

        $this->layout->content = View::make('users.show', compact('user'));
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id) {

        // Find the user
        // @todo Using the find method should throw a ModelNotFoundException which should be caught to show a proper 404 page
        // @todo could probably reuse the show method
        $user = $this->userRepo->find($id);
        if ($user === null)
        {
            App::abort(404, 'User not found!');
        }

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

        $input = Input::all(); // @todo this should only pass what's strictly needed using Input::only()
        $repoResponse = $this->userRepo->update($id, $input);

        if ($repoResponse->isFailure()) {
            return Redirect::route('users.edit', ['id' => $id])->withInput()->with('message', 
                new ViewMessage(ViewMessage::DANGER, $repoResponse->getMessage()) // @todo make ViewMessage a dependency injection
            );
        }

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
     * Blocks the specified user 
     * 
     * @param  integer $id User id to block
     * @return Response
     */
    public function block($id) {

        // @todo what about security here?

        // Find the user
        // @todo Using the find method should throw a ModelNotFoundException which should be caught to show a proper 404 page
        // @todo could probably reuse the show method
        $user = $this->userRepo->find($id);
        if ($user === null) {
            App::abort(404, 'User not found!');
        }

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
        // @todo Using the find method should throw a ModelNotFoundException which should be caught to show a proper 404 page
        // @todo could probably reuse the show method
        $user = $this->userRepo->find($id);
        if ($user === null) {
            App::abort(404, 'User not found!');
        }

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
        // @todo Using the find method should throw a ModelNotFoundException which should be caught to show a proper 404 page
        // @todo could probably reuse the show method
        $user = $this->userRepo->find($id);
        if ($user === null) {
            App::abort(404, 'User not found!');
        }
        
        // Deactivate the user
        // @todo add conditional error handling
        $user->deactivate();
        
        return Redirect::route('users.show', ['id' => $id])->with('message', 
            new ViewMessage(ViewMessage::WARNING, 'User deactivated!') // @todo make ViewMessage a dependency injection, move to language file
        );
    }


    /**
     * Shows the register page for user with deactivated accounts
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

        $user = $this->usersResource->requestActivation($inputData);

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
        try {
            $user  = $this->usersResource->showByActivationToken($token);
        } catch (UsersResourceException $e) {
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

        $user = $this->usersResource->activate(array_merge(
                array('token' => $token),
                Input::all()
            ));

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
     * @param  array $inputData Array with input data, must have 'email' key
     * @return ProcessResponse
     */
    private function processRequestPasswordReset($inputData) {

        //get email and validate        
        $rules = [
            'email' => 'required|email'
        ];

        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            return new ProcessResponse(false, $validator->errors(), ViewMessage::DANGER);            
        }

        //search the user by email address
        $email = $inputData['email'];
        $user = $this->userRepo->findByEmail($email);

        if ((!$user) || ($user && !$user->isRequestPasswordResetAllowed()) ) {
            return new ProcessResponse(false, 'No valid user account with that email could be found.', ViewMessage::WARNING);            
        }

        //generate password reset token
        $token = $user->generatePasswordResetToken();

        //generate password reset URL
        $passwordResetUrl = route('users.passwordResetInit', ['token' => $token]);

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

        $inputData = Input::all();
        $resp = $this->processRequestPasswordReset($inputData);

        if ($resp->isFailure()) {
            return Redirect::route('users.requestPasswordResetInit')->withInput()->with(
                'message', new ViewMessage($resp->getHolder(), $resp->getMessage()));
        }

        // @todo remove the data from the view, is just for debgging:
        $this->layout->content = View::make('users.requestPasswordReset', $resp->getHolder());

    }


    /**
     * Tries to initiate the user password reset process, from the admin flow
     * 
     * @return Response
     */
    public function requestPasswordResetAdmin($id) {

        $inputData = Input::all();
        $resp = $this->processRequestPasswordReset($inputData);

        if ($resp->isFailure()) {
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
     * @return Response
     */
    public function passwordResetInit($token) {

        //search user by reset pass token
        $user = $this->userRepo->findByPasswordResetToken($token);
        if (!$user) {
            Log::warning('User NOT found by reset password token: ' . $token);
            return View::make('users.requestPasswordResetBadToken');
        }

        //validate if password reset is allowed
        $ttl = Config::get('app.resetPasswordTokenTtlHours', 24);
        if (!$user->isPasswordResetAllowed($token, $user->email, $ttl)) {
            Log::warning('User reset password token expired!: ' . $token);
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
     * @return Response
     */
    public function passwordReset($token) {


        //get user by token
        $user = $this->userRepo->findByPasswordResetToken($token);
        if (!$user) {
            return Redirect::route('users.passwordResetInit', ['token' => $token])->withInput()
                ->with('message', new ViewMessage(ViewMessage::DANGER, 
                    'The token and/or email do not match to a valid password reset request.')
                );
        }

        //validate form input
        $rules = $user->getPasswordRules();
        $rules = array_merge($rules, array(
            'email' => 'required|email'
            ));
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::route('users.passwordResetInit', ['token' => $token])->withInput()
                ->with('message', new ViewMessage(ViewMessage::DANGER, $validator->errors())
                );
        }

        //try to set password...
        $ttl = Config::get('app.resetPasswordTokenTtlHours');
        if (!$user->resetPassword($token, Input::get('email'), Input::get('password'), $ttl)) {
            return Redirect::route('users.passwordResetInit', ['token' => $token])->withInput()
                ->with('message', new ViewMessage(ViewMessage::DANGER, 
                    'The token and/or email do not match to a valid password reset request.')
                );
        }

        $this->layout->content = View::make('users.passwordReset');

    }


}