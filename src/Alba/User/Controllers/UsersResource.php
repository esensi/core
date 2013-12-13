<?php namespace Alba\User\Controllers;

use \Exception;

use Alba\Core\Controllers\Resource;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Name;
use Alba\User\Models\Role;
use Alba\User\Models\User;
use Alba\User\Controllers\TokensResource;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\UserInterface;

/**
 * Custom exception handler for UsersResource
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Exceptions\ResourceException
 */
class UsersResourceException extends ResourceException {}

/**
 * Users Resource
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Resource
 */
class UsersResource extends Resource {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'user';

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
     * Inject dependencies
     *
     * @var Alba\User\Models\User $user
     * @var Alba\User\Models\Name $name
     * @var Alba\User\Models\Role $role
     * @var Alba\User\Controllers\TokensResource $tokensResource
     * @return UsersResource;
     */
    public function __construct(User $user, Name $name, Role $role, TokensResource $tokensResource)
    {
        $this->model = $user;
        $this->name = $name;
        $this->role = $role;
        $this->resources['token'] = $tokensResource;

        // Bind auth.login event listener
        Event::listen('auth.login', function(User $user, $remember){
            $user->authenticated_at = Carbon::now();
            return $user->forceSave();
        });
    }

    /**
     * Return an array of user name titles
     *
     * @return array
     */
    public function titles()
    {
        $ttl = Config::get('alba::user.names.ttl.titles', 10);
        $titles = $this->name->whereNotNull('title')->distinct()->remember($ttl)->lists('title');
        $tags = array_unique(array_merge($titles, $this->language('names.titles', [])));
        return array_values($tags); // @note required because array_unique is a numerically associative array
    }

    /**
     * Return an array of user name suffixes
     *
     * @return array
     */
    public function suffixes()
    {
        $ttl = Config::get('alba::user.names.ttl.suffixes', 10);
        $suffixes = $this->name->whereNotNull('suffix')->distinct()->remember($ttl)->lists('suffix');
        $tags = array_unique(array_merge($suffixes, $this->language('names.suffixes', [])));
        return array_values($tags); // @note required because array_unique is a numerically associative array
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
            $this->throwException($this->language('errors.validate'));
        }
        
        // User will need to be active and not blocked
        // Check the credentials with a real login
        if (!Auth::attempt(array_merge($credentials, $extras), $remember))
        {
            $this->throwException($this->language('errors.authenticate'));
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
            $this->throwException($this->language('errors.show_by_email'));
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
    public function showByActivationToken($token, $isExpired = false) {

        // Get the user with the matching token
        $object = $this->model->whereActivationToken($token, $isExpired)->first();
        if (!$object)
        {
            $this->throwException($this->language('errors.show_by_activation_token'));
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
            $this->throwException($this->language('errors.show_by_password_reset_token'));
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
        $roles = $this->role->whereIn('name', $this->model->defaultRoles)->get();

        // Use a transaction so everything fails if one fails
        DB::transaction(function() use ($user, $name, $roles)
        {
            // Save the user
            if (!$user->save($user->rulesForStoring))
            {
                $this->throwException($user->errors(), $this->language('errors.store'));
            }
            
            // Attach Roles to user
            $user->attachRoles($roles);

            // Save name with relationship to user
            $name->user()->associate($user);
            if (!$name->save($name->rulesForStoring))
            {
                $this->throwException($name->errors(), $this->language('errors.store'));
            }

        });

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
        
        // Use a transaction so everything fails if one fails
        DB::transaction(function() use ($user, $name)
        {
            
            // Update user if it's changed
            if ( count($user->getDirty()) )
            {
                if (!$user->save($user->rulesForUpdate))
                {
                    $this->throwException($user->errors(), $this->language('errors.update'));
                }
            }
            
            // Update user if it's changed
            if ( count($name->getDirty()) )
            {
                if (!$name->save($name->rulesForStoring))
                {
                    $this->throwException($name->errors(), $this->language('errors.update'));
                }
            }
        });

        return $user;
    }

    /**
     * Synchronize the roles on the user
     *
     * @param integer $id of user
     * @param array $roles ids
     * @return Model
     * 
     */
    public function syncRoles($id, $roles)
    {
        // Update user attributes
        $object = $this->show($id);
                
        // Sync assigned roles
        try
        {
            $object->roles()->sync($roles);
        }
        catch (Exception $e)
        {
            $this->throwException($e->getMessage());
        }

        return $object;
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
            $this->throwException($this->language('errors.activation_not_allowed'));
        }

        DB::transaction(function() use ($object)
        {
            // @todo: remove all existing activation tokens from user

            // Generate activation token
            $activationToken = $this->resources['token']->createNewActivation($object);

            // Attach token to user
            $object->tokens()->attach($activationToken->id);

            // Deactivate user
            $this->deactivate($object->id);
        });
        
        // Send activation email to user
        if( $sendEmail )
        {
            $this->emailActivation($object, $object->activationToken->token);
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
        $templates = Config::get('alba::user.views.emails.reset_activation');
        $data = ['user' => $object->toArray(), 'token' => $token];
        $subject = $this->language('subjects.reset_activation');
        Mail::send($templates, $data, function($message) use ($object, $subject)
        {
            $message->to($object->email, $object->fullName)
                ->subject($subject);
        });
    }

    /**
     * Activate the specified user
     * 
     * @param integer $id
     * @return User
     * @throws UsersResourceException
     */
    public function activate($id)
    {
        $object = $this->show($id);
        
        // Activate the user
        if (!$object->activate())
        {
            $this->throwException($object->errors(), $this->language('errors.activate'));
        }

        return $object;
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
    public function activateWithToken($token, $newPassword = [])
    {
        // Get the user by the activation token
        $object = $this->showByActivationToken($token);

        // Activate the user
        DB::transaction(function() use ($object)
        {
            // Activate user
            if (!$object->activate())
            {
                $this->throwException($object->errors(), $this->language('errors.activate'));
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
                $this->throwException($object->errors(), $this->language('errors.set_password'));
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
            $this->throwException($this->language('errors.password_reset_not_allowed.'));
        }

        DB::transaction(function() use ($object)
        {
            // @todo: remove all existing password reset tokens from user

            // Generate password reset token
            $resetToken = $this->resources['token']->createNewPasswordReset($object);

            // Attach token to user
            $object->tokens()->attach($resetToken->id);

            // Remove existing password
            if(!$object->resetPassword())
            {
                $this->throwException($object->errors(), $this->language('errors.password_reset'));
            }
        });
        
        // Send password reset email to user
        if( $sendEmail )
        {
            $this->emailPasswordReset($object, $object->passwordResetToken->token);
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
        $templates = Config::get('alba::user.views.emails.reset_password');
        $data = ['user' => $object->toArray(), 'token' => $token];
        $subject = $this->language('subjects.reset_password');
        Mail::send($templates, $data, function($message) use ($object, $subject)
        {
            $message->to($object->email, $object->fullName)
                ->subject($subject);
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
                $this->throwException($object->errors(), $this->language('errors.set_password'));
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
            $this->throwException($object->errors(), $this->language('errors.block'));
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
            $this->throwException($object->errors(), $this->language('errors.unblock'));
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
            $this->throwException($object->errors(), $this->language('errors.deactivate'));
        }
        return $object;
    }

}