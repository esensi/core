<?php namespace Alba\User\Resources;

use \Exception;

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
class UsersResourceException extends \AlbaCoreResourceException {}

/**
 * Users Resource
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Resources\Resource
 */
class UsersResource extends \AlbaCoreResource {

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
    protected $exception = 'Alba\User\Resources\UsersResourceException';

    /**
     * The default attributes for searching
     * 
     * @var array $defaults
     */
    protected $defaults = [
        'order' => 'authenticated_at',
        'sort' => 'desc',
        'max' => 25,
    ];

    /**
     * Inject dependencies
     *
     * @var Alba\User\Models\User $user
     * @var Alba\User\Models\Name $name
     * @var Alba\User\Models\Role $role
     * @var Alba\User\Resources\TokensResource $tokensResource
     * @return UsersResource;
     */
    public function __construct(\AlbaUser $user, \AlbaName $name, \AlbaRole $role, \AlbaTokensResource $tokensResource)
    {
        $this->setModel($user);
        $this->setModel($name, 'name');
        $this->setModel($role, 'role');
        $this->setResource($tokensResource, 'token');

        $this->setDefaults($this->defaults);
        
        // Bind auth.login event listener
        Event::listen('auth.login', function(\AlbaUser $user, $remember){
            $user->authenticated_at = Carbon::now();
            return $user->forceSave();
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
        $object = $this->getModel()->whereEmail($email)->first();
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
        $object = $this->getModel()->whereActivationToken($token, $isExpired)->first();
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
        $object = $this->getModel()->wherePasswordResetToken($token, $isExpired)->first();
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
        $model = $this->getModel();
        $user = new $model();
        $user->fill(array_only($attributes, $user->getFillable()));
        $user->active = false; // new users should not be active
        $user->blocked = false; // new users should not be blocked
        $user->password_updated_at = Carbon::now();

        // Create the name
        $name = $this->getModel('name');
        $name->fill(array_only($attributes, $name->getFillable()));
                
        // Get default Roles to attach to a new user
        $roles = $this->getModel('role')->whereIn('name', $this->getModel()->defaultRoles)->get();

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
            if ( count($user->getDirty()) || !$user->validate($user->rulesForUpdating))
            {
                if (!$user->save($user->rulesForUpdating))
                {
                    $this->throwException($user->errors(), $this->language('errors.update'));
                }
            }
            
            // Update user if it's changed
            if ( count($name->getDirty()) || !$name->validate($name->rulesForUpdating))
            {
                if (!$name->save($name->rulesForUpdating))
                {
                    $this->throwException($name->errors(), $this->language('errors.update'));
                }
            }
        });

        return $user;
    }

    /**
     * Update the user's password
     *
     * @param integer $id
     * @param string $attributes
     * @return Model
     * 
     */
    public function updatePassword($id, $attributes)
    {
        $object = $this->show($id);
        $object->fill(array_only($attributes, $object->getFillable()));
        $object->password_updated_at = Carbon::now();

        // Verify that the old password matches
        $object->old_password = $attributes['old_password'];
        if(!Hash::check($object->old_password, $object->getOriginal('password')))
        {
            $this->throwException($this->language('errors.old_password_mismatch')); 
        }

        // Update the password
        if (!$object->save($object->rulesForNewPassword))
        {
            $this->throwException($object->errors(), $this->language('errors.update_password'));
        }

        return $object;
    }

    /**
     * Update the user's email address
     *
     * @param integer $id
     * @param array $attributes
     * @return Model
     * 
     */
    public function updateEmail($id, $attributes)
    {
        $object = $this->show($id);
        $object->fill(array_only($attributes, $object->getFillable()));
        if (!$object->save($object->rulesForUpdatingEmail))
        {
            $this->throwException($object->errors(), $this->language('errors.update_email'));
        }

        return $object;
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
            $activationToken = $this->getResource('token')->createNewActivation($object);

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
            $resetToken = $this->getResource('token')->createNewPasswordReset($object);

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

    /**
     * Return an array of user name titles
     *
     * @param string $key
     * @return array
     */
    public function titles($key = null)
    {
        $ttl = Config::get('alba::user.names.ttl.titles', 10);
        $dbTags = $this->getModel('name')->distinct()->remember($ttl)->listTitles($key);
        $langTags = $this->language('names.titles', []);
        $configTags = array_combine($langTags, $langTags);
        $tags = array_unique(array_merge($configTags, $dbTags));
        return is_null($key) ? array_values($tags) : $tags;
    }

    /**
     * Return an array of user name suffixes
     *
     * @param string $key
     * @return array
     */
    public function suffixes($key = null)
    {
        $ttl = Config::get('alba::user.names.ttl.suffixes', 10);
        $dbTags = $this->getModel('name')->distinct()->remember($ttl)->listSuffixes($key);
        $langTags = $this->language('names.suffixes', []);
        $configTags = array_combine($langTags, $langTags);
        $tags = array_unique(array_merge($configTags, $dbTags));
        return is_null($key) ? array_values($tags) : $tags;
    }
}