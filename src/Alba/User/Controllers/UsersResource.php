<?php namespace Alba\User\Controllers;

use \Exception;

use Alba\Core\Controllers\Resource;
use Alba\Core\Utils\StringUtils;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Name;
use Alba\User\Models\Role;
use Alba\User\Models\Token;
use Alba\User\Models\User;
use Alba\User\Controllers\TokensResource;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersResourceException extends ResourceException {}

class UsersResource extends Resource {

    /**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = 'UsersResourceException';

    /**
     * The Name model
     *
     * @var Alba\User\Models\Name
     */
    protected $name;

    /**
     * Inject dependencies
     *
     * @var Alba\User\Models\User $user
     * @var Alba\User\Models\Name $name
     * @var Alba\User\Controllers\TokensResource $tokensResource
     */
    public function __construct(User $user, Name $name, TokensResource $tokensResource) {
        $this->model = $user;
        $this->name = $name;
        $this->tokensResource = $tokensResource;
    }

    /**
     * Find the user by the activation token, verifying that it hasn't expired.
     * 
     * @param string $token The activation token
     * @return User
     * @throws UsersResourceException If an error ocurs
     */
    public function showByActivationToken($token) {

        $user = $this->model->whereActivationToken($token)->first();
        if (!$user)
        {
            $this->throwExeception('The activation token is not found!');
        }

        // Make sure token is still valid
        $ttl = Config::get('app.activationTokenTtlHours', 24);
        if (!$user->isActivateAllowed($token, $ttl))
        {
            $this->throwExeception('The activation token has expired!');
        }

        return $user;
    }

    /**
     * Find the user by the password reset token, verifying that it hasn't expired.
     * 
     * @param string $token
     * @return User
     * @throws UsersResourceException
     */
    public function showByPasswordResetToken($token) {

        // Get the user with the matching token
        $user = $this->model->wherePasswordResetToken($token)->first();
        if (!$user) 
        {
            $this->throwExeception('The password reset token is not found!');
        }

        // Make sure token is still valid
        $ttl = Config::get('app.activationTokenTtlHours', 24);
        if (!$user->isPasswordResetAllowed($token, null, $ttl))
        {
            $this->throwExeception('The password reset token has expired!');
        }

        return $user;
    }
    
    /**
     * @see ResourceInterface::store
     */
    public function store($attributes)
    {
        // Validate user
        $user = new User();
        $user->fill($attributes);
        $user->active = false; // new users should not be active
        $user->blocked = false; // new users should not be blocked
        if (!$user->validate($this->model->rulesForStoring))
        {
            $this->throwExeception($user->errors());
        }

        // Validate name
        $name = new Name();
        $name->fill($attributes);
        if (!$name->validate($this->name->rulesForNameOnly))
        {
            $this->throwExeception($name->errors());
        }
                
        // Get default Roles to attach to a new user
        $roles = Role::whereIn('name', $this->model->defaultRoles)->get();

        // Now save the user and name
        try 
        {
            // Use a transaction so everything fails if one fails
            DB::transaction(function() use ($user, $name, $roles)
            {
                // Save the user first
                if (!$user->save($this->model->rulesForStoring))
                {
                    $this->throwExeception($user->errors());
                }
                
                // Attach Roles to user
                foreach ($roles as $role) {
                    $user->attachRole($role);
                }                

                // Save name with relationship to user
                $name->user()->associate($user);
                if (!$name->save($this->name->rulesForStoring))
                {
                    $this->throwExeception($name->errors());
                }

            });
        }
        catch (UsersResourceException $e)
        {
            throw $e;
        }
        catch (Exception $e) 
        {
            $this->throwExeception('There was an unexpected error trying to save the user. Please contact a system administrator if this error persists.');
        }

        return $user;

    }

    /**
     * @see ResourceInterface::update
     */
    public function update($id, $attributes)
    {
        // Find user
        $user = $this->show($id);

        // Update user attributes
        $user->fill($attributes);
        if (!empty($user->getDirty()))
        {
            if (!$user->validate($user->rulesForUpdate))
            {
                $this->throwNew($user->errors());
            }
        }

        // Update name attributes
        $name = $user->name;
        $name->fill($attributes);
        if (!empty($name->getDirty()))
        {
            if (!$name->validate($name->rulesForStoring))
            {
                $this->throwNew($name->errors());
            }
        }
        
        // Now save the user and name
        try
        {
            // Use a transaction so everything fails if one fails
            DB::transaction(function() use ($user, $name)
            {
                
                // Update user if it's changed
                if (!empty($user->getDirty()))
                {
                    if (!$user->save($user->rulesForUpdate))
                    {
                        $this->throwNew($user->errors());
                    }
                }
                
                // Update user if it's changed
                if (!empty($name->getDirty()))
                {
                    if (!$name->save($name->rulesForStoring))
                    {
                        $this->throwNew($name->errors());
                    }
                }
            });
        } 
        catch (UsersResourceException $e)
        {
            throw $e;
        }
        catch (Exception $e)
        {
            throw new UsersResourceException('There was an unexpected error trying to update the user. Please contact a system administrator if this error persists.');
        }

        return $user;
    }

    /**
     * Process login attempt for a user
     * 
     * @param array $inputData
     * @return User
     * @throws UsersResourceException If an error occurs or login not allowed.
     */
    public function login($inputData) 
    {   
        // Get the account inputs needed for authentication
        $validator = Validator::make($inputData, $this->model->rulesForLogin);
        if ($validator->fails())
        {
            $this->throwNew($validator->messages());
        }

        // Validate the credentials with a fake attempt
        if (!Auth::validate($inputData))
        {
            $this->throwNew('Your email and/or password are incorrect!');                        
        }
        
        // User will need to be active and not blocked
        $credentials = array_merge($inputData, ['active' => true, 'blocked' => false]);

        // Check the credentials with a real login
        if(!Auth::attempt($credentials))
        {
            $message = Auth::getProvider()->retrieveByCredentials($inputData)->getLoginAllowedMessage(); // @todo find another way to compute the messages or show a more generic one
            $this->throwNew($message);
        }

        // Log authentication
        // @todo this should be bound as a listener on auth.login
        Auth::user()->doLoginActions();
                
        return Auth::user();
    }

    /**
     * Logs the user out
     * 
     */
    public function logout() {
        Auth::logout();
    }

    /**
     * Checks the user supplied email to see if corresponds to an
     * account that can be activated... if that's the case, it generates
     * a new activation token for that user.
     *
     * @param array $inputData Data containing the email of user to rquest activation
     * @return User The user who's account has been asked to activate
     * @throws UsersResourceException If an error ocurs
     */
    public function requestActivation($inputData) {
        
        $rules = $this->model->rulesForRequestActivation;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails())
        {
            $this->throwNew($validator->errors(), 'UsersResource::requestActivation - Error 1');
        }

        //search the user by email address
        $email = $inputData['email'];
        $user = $this->model->whereEmail($email)->first();
        if (!$user || ($user && !$user->isRequestActivationAllowed()) )
        {
            $this->throwNew('No valid user account with that email could be found.', 'UsersResource::requestActivation - Error 2');   
        }

        DB::transaction(function() use ($user)
        {
            // Generate activation token        
            $token = $this->tokensResource->generateActivation();

            // Set user with token
            $user->tokens()->attach($token->id);  
            
            //@todo: check if a token already exists for this user, and only keep the last one
        });
        
        return $user;
    }

    /**
     * Find the user account using the token, validate the password.
     * If everything is ok, set password and activate account
     *
     * @param array $inputData
     * @return User
     * @throws UsersResourceException
     */
    public function activate($inputData) {

        $rules = $this->model->rulesForActivate;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails())
        {
            $this->throwExeception($validator->errors(), 'UsersResource::activate - Error 1');
        }

        // Get the user by the activation token
        $user = $this->showByActivationToken($inputData['token']);

        // Activate the user
        DB::transaction(function() use ($user, $inputData)
        {
            // Activate user
            $ttl = Config::get('app.activationTokenTtlHours', 24);
            if (!$user->activate($inputData['token'], $inputData['password'], $ttl))
            {
                $this->throwExeception('User account not activated due to internal problems. Please contact a system administrator if the problem persists.', 'UsersResource::activate - Error 3');
            }

            // Delete activation token
            $token = $user->activationToken;
            $token->delete();

        });

        return $user;
    }

    /** 
     * Process the password reset request, validating the email, generating
     * a new password request token. 
     *
     * @param array $inputData
     * @return User
     * @throws UsersResourceException
     */
    public function requestPasswordReset($inputData) {

        $rules = $this->model->rulesForRequestPasswordReset;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            $this->throwExeception($validator->errors(), 'UsersResource::requestPasswordReset - Error 1');
        }

        // Find user by the email address
        $email = $inputData['email'];
        $user = $this->model->whereEmail($email)->first();
        if ((!$user) || ($user && !$user->isRequestPasswordResetAllowed()) ) {
            $this->throwExeception('No valid user account with that email could be found.', 'UsersResource::requestActivation - Error 2');   
        }

        DB::transaction(function() use ($user)
        {
            // Generate activation token
            $token = $this->tokensResource->generatePasswordReset();
            
            // Set user with token
            $user->tokens()->attach($token->id);

            //@todo: check if a token already exists for this user, and only keep the last one
        });

        return $user;
    }

    /**
     * Tries to reset the password of a user
     * 
     * @return User
     * @throws UsersResourceException
     */
    public function resetPassword($inputData)
    {

        //validate form input
        $rules = $this->model->rulesForResetPassword;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails())
        {
            $this->throwExeception($validator->errors());
        }

        // Get the user by the password reset token
        $user = $this->showByPasswordResetToken($inputData['token']);

        DB::transaction(function() use ($user, $inputData)
        {
            // Reset the password
            $ttl = Config::get('app.resetPasswordTokenTtlHours');
            if (!$user->resetPassword($inputData['token'], $inputData['email'], $inputData['password'], $ttl)) 
            {
                $this->throwExeception('The token and/or email do not match to a valid password reset request.');            
            }

            // Delete the password reset token
            $token = $user->passwordResetToken;
            $token->delete();
        });        

        return $user;
    }

}