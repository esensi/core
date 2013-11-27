<?php namespace Alba\User\Controllers;

use \Exception;

use Alba\Core\Controllers\Resource;
use Alba\Core\Utils\StringUtils;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Name;
use Alba\User\Models\Role;
use Alba\User\Models\User;
use Alba\User\Controllers\TokensResource;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\UserInterface;

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
     * The Role model
     *
     * @var Alba\User\Models\Role
     */
    protected $role;

    /**
     * Injected resources
     *
     * @var array
     */
    protected $resources;

    /**
     * Inject dependencies
     *
     * @var Alba\User\Models\User $user
     * @var Alba\User\Models\Name $name
     * @var Alba\User\Models\Role $role
     * @var Alba\User\Controllers\TokensResource $tokensResource
     */
    public function __construct(User $user, Name $name, Role $role, TokensResource $tokensResource) {
        $this->model = $user;
        $this->name = $name;
        $this->role = $role;
        $this->resources['token'] = $tokensResource;

        // Bind auth.login event listener
        Event::listen('auth.login', function(User $user, $remember){
            $user->authenticated_at = Carbon::now();
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
        // Validate the credentials with a fake attempt
        if (!Auth::validate($credentials))
        {
            $this->throwException(Lang::get('alba::user.failed.validate'));
        }
        
        // User will need to be active and not blocked
        // Check the credentials with a real login
        if (!Auth::attempt(array_merge($credentials, $extras), $remember))
        {
            $this->throwException(Lang::get('alba::user.failed.authenticate'));
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
     */
    public function showByEmail($email)
    {
        $object = $this->model->whereEmail($email)->first();
        if(!$object)
        {
            $this->throwException(Lang::get('alba::user.failed.show_by_email'));
        }
        return $object;
    }

    /**
     * Find the user by the activation token, verifying that it hasn't expired.
     * 
     * @param string $token
     * @param boolean $isExpired
     * @return User
     * @throws UsersResourceException
     */
    public function showByActivationToken($token, $isExpired) {

        // Get the user with the matching token
        $object = $this->model->whereActivationToken($token, $isExpired)->first();
        if (!$object)
        {
            $this->throwException(Lang::get('alba::user.failed.show_by_activation_token'));
        }

        return $object;
    }

    /**
     * Find the user by the password reset token, verifying that it hasn't expired.
     * 
     * @param string $token
     * @param boolean $isExpired
     * @return User
     * @throws UsersResourceException
     */
    public function showByPasswordResetToken($token, $isExpired = false) {

        // Get the user with the matching token
        $object = $this->model->wherePasswordResetToken($token, $isExpired)->first();
        if (!$object)
        {
            $this->throwException(Lang::get('alba::user.failed.show_by_password_reset_token'));
        }

        return $object;
    }
    
    /**
     * @see ResourceInterface::store
     */
    public function store($attributes)
    {
        // Create the user
        $user = $this->model;
        $user->fill(array_only($attributes, $user->getFillable()));
        $user->active = false; // new users should not be active
        $user->blocked = false; // new users should not be blocked

        // Create the name
        $name = $this->name;
        $name->fill(array_only($attributes, $name->getFillable()));
                
        // Get default Roles to attach to a new user
        $role = $this->role;
        $roles = $role::whereIn('name', $this->model->defaultRoles)->get();

        // Now save the user and name
        try
        {
            // Use a transaction so everything fails if one fails
            DB::transaction(function() use ($user, $name, $roles)
            {
                // Save the user
                if (!$user->save($user->rulesForStoring))
                {
                    $this->throwException($user->errors(), Lang::get('alba::user.failed.store'));
                }
                
                // Attach Roles to user
                $user->attachRoles($role);

                // Save name with relationship to user
                $name->user()->associate($user);
                if (!$name->save($name->rulesForStoring))
                {
                    $this->throwException($name->errors(), Lang::get('alba::user.failed.store'));
                }

            });
        }
        catch (Exception $e)
        {
            $this->throwException($e->getMessage());
        }

        return $user;

    }

    /**
     * @see ResourceInterface::update
     */
    public function update($id, $attributes)
    {
        // Update user attributes
        $user = $this->show($id);
        $user->fill(array_only($attributes, $user->getFillable()));

        // Update name attributes
        $name = $user->name;
        $name->fill(array_only($attributes, $name->getFillable()));
        
        // Now save the user and name
        try
        {
            // Use a transaction so everything fails if one fails
            DB::transaction(function() use ($user, $name)
            {
                
                // Update user if it's changed
                if ( count($user->getDirty()) )
                {
                    if (!$user->save($user->rulesForUpdate))
                    {
                        $this->throwException($user->errors(), Lang::get('alba::user.failed.update'));
                    }
                }
                
                // Update user if it's changed
                if ( count($name->getDirty()) )
                {
                    if (!$name->save($name->rulesForStoring))
                    {
                        $this->throwException($name->errors(), Lang::get('alba::user.failed.update'));
                    }
                }
            });
        } 
        catch (Exception $e)
        {
            $this->throwException($e->getMessage());
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
     * Find the user by email and attempts to create a new
     * activation token for the user. Optionally it sends an
     * activation email to the user.
     *
     * @param string $email of user
     * @param boolean $sendEmail
     * @return User
     * @throws UsersResourceException
     */
    public function resetActivation($email, $sendEmail = true)
    {
        $object = $this->showByEmail($email);
        
        // Check if user is allowed to activate
        if (!$object->isActivationAllowed())
        {
            $this->throwException(Lang::get('alba::user.failed.activation_not_allowed'));
        }

        DB::transaction(function() use ($object)
        {
            // @todo: remove all existing activation tokens from user

            // Generate activation token
            $activationToken = $this->resources['token']->createNewActivation($object);

            // Attach token to user
            $object->tokens()->attach($activationToken->id);
        });
        
        // Send activation email to user
        if( $sendEmail )
        {
            $this->emailActivation($object, $activationToken->token);
        }

        return $object;
    }

    /**
     * Email activation link to user
     *
     * @param UserInterface $object
     * @param string $token
     * @return void
     * 
     */
    public function emailActivation(UserInterface $object, $token)
    {
        $templates = ['emails.html.users.activation', 'emails.text.users.activation'];
        $data = ['user' => $object->toArray(), 'token' => $token];
        Mail::send($templates, $data, function($message) use ($object)
        {
            $message->to($object->email, $object->fullName)
                ->subject(Lang::get('alba::user.subject.activation'));
        });
    }

    /**
     * Find ther user by activation token then activate the user.
     * Optionally set a new password
     *
     * @param string $token
     * @param array $newPassword
     * @return User
     * @throws UsersResourceException
     */
    public function activate($token, $newPassword = [])
    {
        // Get the user by the activation token
        $object = $this->showByActivationToken($token);

        // Activate the user
        DB::transaction(function() use ($object)
        {
            // Activate user
            if (!$object->activate())
            {
                $this->throwException($object->errors(), Lang::get('alba::user.failed.activate'));
            }

            // Delete activation token
            $token = $object->activationToken;
            $token->delete();
        });

        // Update the password at the same time
        if( !empty($newPassword) )
        {
            if(!$object->savePassword($newPassword))
            {
                $this->throwException($object->errors(), Lang::get('alba::user.failed.update_password'));
            }
        }

        return $object;
    }

    /** 
     * Find the user by email and attempts to create a new
     * password reset token for the user. Optionally it sends
     * password reset email to the user. 
     *
     * @param string $email
     * @param boolean $sendEmail
     * @return User
     * @throws UsersResourceException
     */
    public function resetPassword($email, $sendEmail = true)
    {
        $object = $this->showByEmail($email);
        
        // Check if user is allowed to activate
        if (!$object->isPasswordResetAllowed())
        {
            $this->throwException(Lang::get('alba::user.failed.password_reset_not_allowed.'));
        }

        DB::transaction(function() use ($object)
        {
            // @todo: remove all existing password reset tokens from user

            // Generate password reset token
            $resetToken = $this->resources['token']->createNewPasswordReset($object);

            // Attach token to user
            $object->tokens()->attach($resetToken->id);
        });
        
        // Send password reset email to user
        if( $sendEmail )
        {
            $this->emailPasswordReset($object, $resetToken->token);
        }

        return $object;
    }

    /**
     * Email password reset link to user
     *
     * @param UserInterface $object
     * @param string $token
     * @return void
     * 
     */
    public function emailPasswordReset(UserInterface $object, $token)
    {
        $templates = ['emails.html.users.reset-password', 'emails.text.users.reset-password'];
        $data = ['user' => $object->toArray(), 'token' => $token];
        Mail::send($templates, $data, function($message) use ($object)
        {
            $message->to($object->email, $object->fullName)
                ->subject(Lang::get('alba::user.subject.reset_password'));
        });
    }

    /**
     * Find ther user by password reset token then save
     * new password for user.
     *
     * @param string $token
     * @param array $newPassword
     * @return User
     * @throws UsersResourceException
     */
    public function setPassword($token, $newPassword = [])
    {
        // Get the user by the password reset token
        $object = $this->showByPasswordResetToken($token);

        // Save the new password
        DB::transaction(function() use ($object, $newPassword)
        {
            if(!$object->savePassword($newPassword))
            {
                $this->throwException($object->errors(), Lang::get('alba::user.failed.set_password'));
            }

            // Delete password reset token
            $token = $object->passwordResetToken;
            $token->delete();
        });

        return $object;
    }

    /**
     * Set the blocked status to true for object
     *
     * @param integer $id of object to block
     * @return User
     * 
     */
    public function block($id)
    {
        $object = $this->show($id);
        if(!$object->block())
        {
            $this->throwException($object->errors(), Lang::get('alba::user.failed.block'));
        }
        return $object;
    }

    /**
     * Set the blocked status to false for object
     *
     * @param integer $id of object to unblock
     * @return User
     * 
     */
    public function unblock($id)
    {
        $object = $this->show($id);
        if(!$object->unblock())
        {
            $this->throwException($object->errors(), Lang::get('alba::user.failed.unblock'));
        }
        return $object;
    }

    /**
     * Set the activation status to false for object
     *
     * @param integer $id of object to deactivate
     * @return User
     * 
     */
    public function deactivate($id)
    {
        $object = $this->show($id);
        if(!$object->deactivate())
        {
            $this->throwException($object->errors(), Lang::get('alba::user.failed.deactivate'));
        }
        return $object;
    }

}