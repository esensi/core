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

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;


class UsersResourceException extends ResourceException {}

class UsersResource extends Resource {

    /**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = 'Alba\User\Controllers\UsersResourceException';

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

        // Bind auth.login event listener
        Event::listen('auth.login', function(User $user, $remember){
            $user->authenticated_at = new Carbon();
            return $user->updateUniques();
        });
    }

    /**
     * Log user in by authenticating with credentials
     *
     * @param array $credentials to find user with
     * @param array $extras to make sure found user can login
     * @param bool $remember user with persistent session
     * @return User
     */
    public function authenticate($credentials, $extras = [], $remember = false)
    {
        // Validate the input data
        $validator = Validator::make($credentials, $this->model->rulesForLogin);
        if ($validator->fails())
        {
            $this->throwNew($validator->messages());
        }

        // Validate the credentials with a fake attempt
        if (!Auth::validate($credentials))
        {
            $this->throwException(null, Lang::get('alba::user.failed.validate'));
        }
        
        // User will need to be active and not blocked
        // Check the credentials with a real login
        if (!Auth::attempt(array_merge($credentials, $extras), $remember))
        {
            $this->throwException(null, Lang::get('alba::user.failed.auth'));
        }

        return Auth::user();
    }

    /**
     * Log user out
     *
     * @return void
     */
    public function unauthenticate()
    {
        Auth::logout();
    }

    /**
     * Show the specificed resource by the email address
     *
     * @param string $email address
     * @return Model
     * 
     */
    public function showByEmail($email)
    {
        $object = $this->model->whereEmail($email)->first();
        if(!$object)
        {
            $this->throwException(null, Lang::get('alba::user.failed.show_by_email'));
        }
        return $object;
    }

    /**
     * Find the user by the activation token, verifying that it hasn't expired.
     * 
     * @param string $token
     * @return User
     * @throws UsersResourceException
     */
    public function showByActivationToken($token) {

        $object = $this->model->whereActivationToken($token)->first();
        if (!$object)
        {
            $this->throwException('The activation token is not found!');
        }

        // Make sure token is still valid
        $ttl = Config::get('app.activationTokenTtlHours', 24);
        if (!$object->isActivateAllowed($token, $ttl))
        {
            $this->throwException('The activation token has expired!');
        }

        return $object;
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
        $object = $this->model->wherePasswordResetToken($token)->first();
        if (!$object) 
        {
            $this->throwException('The password reset token is not found!');
        }

        // Make sure token is still valid
        $ttl = Config::get('app.activationTokenTtlHours', 24);
        if (!$object->isPasswordResetAllowed($token, null, $ttl))
        {
            $this->throwException('The password reset token has expired!');
        }

        return $object;
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
            $this->throwException($user->errors());
        }

        // Validate name
        $name = new Name();
        $name->fill($attributes);
        if (!$name->validate($this->name->rulesForNameOnly))
        {
            $this->throwException($name->errors());
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
                    $this->throwException($user->errors());
                }
                
                // Attach Roles to user
                foreach ($roles as $role) {
                    $user->attachRole($role);
                }                

                // Save name with relationship to user
                $name->user()->associate($user);
                if (!$name->save($this->name->rulesForStoring))
                {
                    $this->throwException($name->errors());
                }

            });
        }
        catch (UsersResourceException $e)
        {
            throw $e;
        }
        catch (Exception $e) 
        {
            $this->throwException('There was an unexpected error trying to save the user. Please contact a system administrator if this error persists.');
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
        $userIsDirty = count($user->getDirty());
        if ($userIsDirty)
        {
            if (!$user->validate($user->rulesForUpdate))
            {
                $this->throwNew($user->errors());
            }
        }

        // Update name attributes
        $name = $user->name;
        $name->fill($attributes);
        $nameIsDirty = count($name->getDirty());
        if ($nameIsDirty)
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
            DB::transaction(function() use ($user, $name, $userIsDirty, $nameIsDirty)
            {
                
                // Update user if it's changed
                if ($userIsDirty)
                {
                    if (!$user->save($user->rulesForUpdate))
                    {
                        $this->throwNew($user->errors());
                    }
                }
                
                // Update user if it's changed
                if ($nameIsDirty)
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
     * Remove the specified resource from storage.
     *
     * @param integer $id of object to remove
     * @param boolean $force delete
     * @return boolean
     * 
     */
    public function destroy($id, $force = false)
    {
        return parent::destroy($id, $force);
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
            $this->throwException($validator->errors(), 'UsersResource::activate - Error 1');
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
                $this->throwException('User account not activated due to internal problems. Please contact a system administrator if the problem persists.', 'UsersResource::activate - Error 3');
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
            $this->throwException($validator->errors(), 'UsersResource::requestPasswordReset - Error 1');
        }

        // Find user by the email address
        $email = $inputData['email'];
        $user = $this->model->whereEmail($email)->first();
        if ((!$user) || ($user && !$user->isRequestPasswordResetAllowed()) ) {
            $this->throwException('No valid user account with that email could be found.', 'UsersResource::requestActivation - Error 2');   
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
     * Email activation link to user
     *
     * @param UserInterface $object
     * @param string $token
     * @return Mail
     * 
     */
    public function emailActivation(UserInterface $object, $token)
    {
        $templates = ['emails.html.users.activation', 'emails.text.users.activation'];
        $data = ['user' => $object->toArray(), 'token' => $token];
        return Mail::send($templates, $data, function($message) use ($object)
        {
            $message->to($object->email, $object->fullName)
                ->subject(Lang::get('alba::user.subject.activation'));
        });
    }

    /**
     * Email password reset link to user
     *
     * @param UserInterface $object
     * @param string $token
     * @return Mail
     * 
     */
    public function emailResetPassword(UserInterface $object, $token)
    {
        $templates = ['emails.html.users.reset-password', 'emails.text.users.reset-password'];
        $data = ['user' => $object->toArray(), 'token' => $token];
        return Mail::send($templates, $data, function($message) use ($object)
        {
            $message->to($object->email, $object->fullName)
                ->subject(Lang::get('alba::user.subject.reset_password'));
        });
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
            $this->throwException($validator->errors());
        }

        // Get the user by the password reset token
        $user = $this->showByPasswordResetToken($inputData['token']);

        DB::transaction(function() use ($user, $inputData)
        {
            // Reset the password
            $ttl = Config::get('app.resetPasswordTokenTtlHours');
            if (!$user->resetPassword($inputData['token'], $inputData['email'], $inputData['password'], $ttl)) 
            {
                $this->throwException('The token and/or email do not match to a valid password reset request.');            
            }

            // Delete the password reset token
            $token = $user->passwordResetToken;
            $token->delete();
        });        

        return $user;
    }

}