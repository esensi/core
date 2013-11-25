<?php namespace Alba\User\Controllers;

use Alba\Core\Contracts\ResourceInterface;
use Alba\Core\Utils\StringUtils;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Name;
use Alba\User\Models\Role;
use Alba\User\Models\Token;
use Alba\User\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UsersResourceException extends ResourceException {}

class UsersResource implements ResourceInterface {


    /**
     * Default user roles to set to new users
     * @var array
     */
    public static $defaultUserRoles = ['user'];

    /**
     * The User model
     * @var Alba\User\Models\User
     */
    protected $user;



    public function __construct(User $user, Name $name, TokensResource $tokensResource) {
        $this->user = $user;
        $this->name = $name;
        $this->tokensResource = $tokensResource;
    }


    /**
     * Returns an array with the attributes that must be sent to the store method
     * @return array
     */
    public function getAttributesForStoring()
    {
        return array_keys(array_merge($this->name->rulesForStoring, $this->user->rulesForStoring));
    }

    /**
     * Returns an array with the attributes that must be sent to the login method
     * @return array
     */
    public function getAttributesForLogin()
    {
        return array_keys($this->user->rulesForLogin);
    }


    /**
     * @see ResourceInterface::index
     */
    public function index($params = []) 
    {

        return $this->user->all();

    }

    /**
     * @see ResourceInterface::store
     */
    public function store($attributes)
    {

        //take user info from data and validate
        $user = new User();
        $user->email = $attributes['email'];
        //users are created deactivated and with no password
        $user->active = false;
        $user->blocked = false;
        if (!$user->validate($this->user->rulesForStoring)) {
            throw new UsersResourceException($user->errors());          
        }

        //take name info from data and validate
        $name = new Name();
        $name->title = $attributes['title'];
        $name->first_name = $attributes['first_name'];
        $name->middle_name = $attributes['middle_name'];
        $name->last_name = $attributes['last_name'];
        $name->suffix = $attributes['suffix'];
        //validate first without checking user, so we don't have to save the user first
        if (!$name->validate($this->name->rulesForNameOnly)) {
            throw new UsersResourceException($name->errors());            
        }
                
        //get default Roles to attach to a new user
        $roles = Role::whereIn('name', self::$defaultUserRoles)->get();

        //user and name are correct... save them!
        try 
        {
            DB::transaction(function() use ($user, $name, $roles) {
                
                if (!$user->save($this->user->rulesForStoring)) {
                    throw new UsersResourceException($user->errors());                    
                }
                
                //attach Roles to user
                foreach ($roles as $role) {
                    $user->attachRole($role);
                }                

                $name->user()->associate($user);
                
                //Log::info("Saving name...");
                if (!$name->save($this->name->rulesForStoring)) {
                    throw new UsersResourceException($name->errors());
                }

            });
        } 
        catch (UsersResourceException $e) 
        {
            throw $e;            
        }
        catch (\Exception $e) 
        {
            throw new UsersResourceException('There was an unexpected error trying to save the user. Please contact a system administrator if this error persists.');
        }

        return $user;

    }

    /**
     * @see ResourceInterface::show
     */
    public function show($id)
    {
        $user = $this->user->find($id);
        if (!$user) {
            throw new UsersResourceException('The user was not found!');            
        }
        return $user;
    }

    /**
     * @see ResourceInterface::update
     */
    public function update($id, $attributes)
    {

        //search user
        $user = $this->show($id);

        //take user info and validate
        $userChanged = false;
        if (isset($attributes['email'])) {
            $userChanged = true;
            $user->email = $attributes['email'];
        }

        if ($userChanged) {
            if (!$user->validate($user->rulesForUpdate)) {
                throw new UsersResourceException($user->errors());
            }
        }

        //take name info from form and validate
        $nameChanged = false;
        $name = $user->name;
        if (isset($attributes['title'])) {
            $nameChanged = true;
            $name->title = $attributes['title'];
        }
        if (isset($attributes['first_name'])) {
            $nameChanged = true;
            $name->first_name = $attributes['first_name'];    
        }        
        if (isset($attributes['middle_name'])) {
            $nameChanged = true;
            $name->middle_name = $attributes['middle_name'];    
        }        
        if (isset($attributes['last_name'])) {
            $nameChanged = true;
            $name->last_name = $attributes['last_name'];    
        }        
        if (isset($attributes['suffix'])) {
            $nameChanged = true;
            $name->suffix = $attributes['suffix'];    
        }
        
        if ($nameChanged) {
            if (!$name->validate($name->rulesForStoring)) {
                throw new UsersResourceException($name->errors());                
            }
        }
        
        //user and name are correct... save them!
        try {
            DB::transaction(function() use ($user, $name, $userChanged, $nameChanged) {
                
                if ($userChanged) {
                    if (!$user->save($user->rulesForUpdate)) {                    
                        throw new UsersResourceException($user->errors());
                    }
                }
                if ($nameChanged) {
                    if (!$name->save($name->rulesForStoring)) {
                        throw new UsersResourceException($name->errors());
                    }
                }

            });
        } 
        catch (UsersResourceException $e) {
            throw $e;
        }
        catch (\Exception $e) {
            throw new UsersResourceException('There was an unexpected error trying to update the user. Please contact a system administrator if this error persists.');
        }        

        return $user;

    }


    /**
     * @see ResourceInterface::destroy
     * 
     */
    public function destroy($id, $force)
    {

    }


    /**
     * Process login attempt for a user
     * 
     * @return User
     * @throws UsersResourceException If an error occurs or login not allowed.
     */
    public function login($inputData) 
    {
        
        // Get the account inputs needed for authentication
        $validator = Validator::make($inputData, $this->user->rulesForLogin);
        if ($validator->fails())
        {
            throw new UsersResourceException($validator->messages());
        }

        // Validate the credentials with a fake attempt
        if (!Auth::validate($inputData))
        {
            throw new UsersResourceException('Your email and/or password are incorrect!');                        
        }
        
        // User will need to be active and not blocked
        $credentials = array_merge($inputData, ['active' => true, 'blocked' => false]);

        // Check the credentials with a real login
        if(!Auth::attempt($credentials))
        {
            $message = Auth::getProvider()->retrieveByCredentials($inputData)->getLoginAllowedMessage(); // @todo find another way to compute the messages or show a more generic one
            throw new UsersResourceException($message);
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
     * 
     * @return User The user who's account has been asked to activate
     * 
     * @throws UsersResourceException If an error ocurs
     */
    public function requestActivation($inputData) {
        
        //$rules = $this->user->getRequestActivationRules();
        $rules = $this->user->rulesForRequestActivation;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            throw new UsersResourceException($validator->errors(), 'UsersResource::requestActivation - Error 1');
        }

        //search the user by email address
        $email = $inputData['email'];
        $user = $this->user->whereEmail($email)->first();
        if ((!$user) || ($user && !$user->isRequestActivationAllowed()) ) {
            throw new UsersResourceException('No valid user account with that email could be found.', 'UsersResource::requestActivation - Error 2');   
        }

        DB::transaction(function() use ($user)
        {
            //generate activation token        
            $token = $this->tokensResource->generateToken(Token::TYPE_ACTIVATION);
            //set user with token
            $user->tokens()->attach($token->id);    

            //Log::info("Generated token ID: " . $token->id);
            
            //@todo: check if a token already exists for this user, and only keep the last one

        });
        
        return $user;

    }



    /**
     * Searches the user by the activation token, verifying that it hasn't expired.
     * 
     * @param string $token The activation token
     * @return User
     * @throws UsersResourceException If an error ocurs
     */
    public function showByActivationToken($token) {

        $user = $this->user->whereActivationToken($token)->first();
        if (!$user) {
            throw new UsersResourceException('The activation token is not found!');
        }

        //validate token
        $ttl = Config::get('app.activationTokenTtlHours', 24);
        if (!$user->isActivateAllowed($token, $ttl)) {
            throw new UsersResourceException('The activation token has expired!');
        }

        return $user;

    }


    /**
     * Search the user account using the token, validate the password.
     * If everything is ok, set password and activate account
     * @param array $inputData Array of input data to proceed with activation (token, password, password_confirm)
     * @return User
     * @throws UsersResourceException If an error ocurs
     */
    public function activate($inputData) {

        //$rules = $this->user->getActivateRules();
        $rules = $this->user->rulesForActivate;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            throw new UsersResourceException($validator->errors(), 'UsersResource::activate - Error 1');
        }

        //@todo: call token resource
        $user = $this->user->whereActivationToken($inputData['token'])->first();
        if (!$user) {
            throw new UsersResourceException('User account not activated due to internal problems. Please contact a system administrator if the problem persists.', 'UsersResource::activate - Error 2');
        }

        //try to activate the user account
        DB::transaction(function() use ($user, $inputData)
        {
            
            //activate user
            $ttl = Config::get('app.activationTokenTtlHours', 24);
            if (!$user->activate($inputData['token'], $inputData['password'], $ttl)) {
                throw new UsersResourceException('User account not activated due to internal problems. Please contact a system administrator if the problem persists.', 'UsersResource::activate - Error 3');
            }

            //no need to detach, cascade clause set on pivot table
            //$user->tokens()->detach($token->id);
            
            //delete token
            $this->tokensResource->destroyByToken($inputData['token']);

        });

        return $user;

    }



    /** 
     * Process the password reset request, validating the email, generating
     * a new password request token. 
     *
     * @param array $inputData Data containing the email of user to rquest activation
     * @return User
     * @throws UsersResourceException If an error ocurs
     */
    public function requestPasswordReset($inputData) {

        //$rules = $this->user->getRequestPasswordResetRules();
        $rules = $this->user->rulesForRequestPasswordReset;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            throw new UsersResourceException($validator->errors(), 'UsersResource::requestPasswordReset - Error 1');
        }

        //search the user by email address
        $email = $inputData['email'];
        $user = $this->user->whereEmail($email)->first();
        if ((!$user) || ($user && !$user->isRequestPasswordResetAllowed()) ) {
            throw new UsersResourceException('No valid user account with that email could be found.', 'UsersResource::requestActivation - Error 2');   
        }

        DB::transaction(function() use ($user)
        {

            //generate activation token
            $token = $this->tokensResource->generateToken(Token::TYPE_PASS_RESET);
            //set user with token
            $user->tokens()->attach($token->id);

            //Log::info("Generated token ID: " . $token->id);

            //@todo: check if a token already exists for this user, and only keep the last one

        });

        return $user;

    }



    /**
     * Searches the user by the password reset token, verifying that it hasn't expired.
     * 
     * @param string $token The password reset token
     * @return User
     * @throws UsersResourceException If an error ocurs
     */
    public function showByPasswordResetToken($token) {

        $user = $this->user->wherePasswordResetToken($token)->first();
        if (!$user) 
        {
            Log::info("password token not found");
            throw new UsersResourceException('The password reset token is not found!');
        }

        //validate token
        $ttl = Config::get('app.activationTokenTtlHours', 24);
        if (!$user->isPasswordResetAllowed($token, null, $ttl))
        {
            Log::info("password token reset not allowed");
            throw new UsersResourceException('The password reset token has expired!');
        }

        return $user;

    }



    /**
     * Tries to reset the password of a user
     * 
     * @return User
     * @throws UsersResourceException If an error ocurs
     */
    public function resetPassword($inputData)
    {

        //validate form input
        //$rules = $this->user->getResetPasswordRules();
        $rules = $this->user->rulesForResetPassword;
        $validator = Validator::make($inputData, $rules);
        if ($validator->fails()) {
            throw new UsersResourceException($validator->errors());
        }

        $user = $this->user->wherePasswordResetToken($inputData['token'])->first();
        if (!$user) 
        {         
            throw new UsersResourceException('The token and/or email do not match to a valid password reset request.');
        }        

        DB::transaction(function() use ($user, $inputData)
        {
            //try to set password...
            $ttl = Config::get('app.resetPasswordTokenTtlHours');
            if (!$user->resetPassword($inputData['token'], $inputData['email'], $inputData['password'], $ttl)) 
            {
                throw new UsersResourceException('The token and/or email do not match to a valid password reset request.');            
            }

            //delete token
            $this->tokensResource->destroyByToken($inputData['token']);

        });        

        return $user;

    }


}