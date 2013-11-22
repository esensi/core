<?php namespace Alba\User\Controllers;

use Alba\Core\Contracts\ResourceInterface;
use Alba\Core\Utils\StringUtils;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Token;
use Alba\User\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UsersResourceException extends ResourceException {}

class UsersResource implements ResourceInterface {


    /**
     * The User model
     * @var Alba\User\Models\User
     */
    protected $user;



    public function __construct(User $user, TokensResource $tokensResource) {
        $this->user = $user;
        $this->tokensResource = $tokensResource;
    }



    /**
     * @see ResourceInterface::index
     */
    public function index($params = []) 
    {

    }

    /**
     * @see ResourceInterface::store
     */
    public function store($attributes)
    {
        
    }

    /**
     * @see ResourceInterface::show
     */
    public function show($id)
    {

    }

    /**
     * @see ResourceInterface::update
     */
    public function update($id, $attributes)
    {

    }

    /**
     * @see ResourceInterface::destroy
     * 
     */
    public function destroy($id, $force)
    {

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
        
        $rules = $this->user->getRequestActivationRules();
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

        $rules = $this->user->getActivateRules();
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

        $rules = $this->user->getRequestPasswordResetRules();
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
        $rules = $this->user->getResetPasswordRules();
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