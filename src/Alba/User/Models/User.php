<?php

namespace Alba\User\Models;

use LaravelBook\Ardent\Ardent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Zizaco\Entrust\HasRole;


use Alba\Core\Utils\StringUtils;

/**
 * User model class
 *
 * @author diego <diego@emersonmedia.com>
 */
class User extends Ardent implements UserInterface {

    use HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';


    //@todo: all this rules get repeated, and there are isntance methods to return them to 
    //Resources. Find a better way to do this.

    /**
     * The attribute rules that Ardent will validate against
     * 
     * @var array
     */
    public static $rules = [
        'email' => ['required', 'email', 'max:128', 'unique:users'], //IMPORTANT: keep unique rule at the end
    ];

    
    public static $passwordRules = [
        'password' => 'required|alpha_num|between:4,256|confirmed',
        'password_confirmation' => 'required|alpha_num|between:4,256'
    ];


    public static $requestActivationRules = [
        'email' => 'required|email'
    ];


    public static $activateRules = [
        'token' => 'required|alpha_num',
        'password' => 'required|alpha_num|between:4,256|confirmed',
        'password_confirmation' => 'required|alpha_num|between:4,256'
    ];


    public static $requestPasswordResetRules = [
        'email' => 'required|email'
    ];


    public static $resetPasswordRules = [
        'token' => 'required|alpha_num',
        'email' => 'required|email',  
        'password' => 'required|alpha_num|between:4,256|confirmed',
        'password_confirmation' => 'required|alpha_num|between:4,256'
    ];


    /**
     * Auto hydrate Ardent model based on input (new models)
     *
     * @var boolean
     */
    public $autoHydrateEntityFromInput = false;

    /**
     * Auto hydrate Ardent model based on input (existing models)
     *
     * @var boolean
     */
    public $forceEntityHydrationFromInput = false;


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    protected $fillable = array('email', 'title', 'first_name', 'middle_name', 'last_name', 'suffix');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Many-to-Many relations with Role
     */
    public function roles() {
        return $this->belongsToMany('Alba\User\Models\Role', 'assigned_roles', 'user_id', 'role_id');
    }

    /**
     * One-to-One reletion with Name
     * @return Name
     */
    public function name() {
        return $this->hasOne('Alba\User\Models\Name');
    }


    /**
     * 
     */
    public function tokens() {
        return $this->belongsToMany('Alba\User\Models\Token');
    }



    /**
     * Returns the validation rules for the password
     * @return array
     */
    public function getPasswordRules() 
    {
        return self::$passwordRules;
    }

    /**
     * Returns the validation rules for the request activation process
     * @return array
     */
    public function getRequestActivationRules() 
    {
        return self::$requestActivationRules;
    }


    /**
     * Returns the validation rules for activate process
     * @return array
     */
    public function getActivateRules() 
    {
        return self::$activateRules;
    }


    /**
     * Returns the validation rules for request password reset process
     * @return array
     */
    public function getRequestPasswordResetRules()
    {
        return self::$requestPasswordResetRules;
    }


    public function getResetPasswordRules() 
    {
        return self::$resetPasswordRules;
    }


    /**
     * Returns a string with the full name of the user
     * 
     * @param  string $format Format pattern. Added for future use.
     * @return string         Full name of the user
     */
    public function getFullName($format = null) {
        return $this->name->getFullName($format);
    }


    /**
     * Returns the user who has the activation token indicated
     * @param  [type] $query [description]
     * @param string $token The activation token
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereActivationToken($query, $token) {

        /*$result = $query
            ->where('id', '=', 18)->first();*/

        return $query
            ->select('users.*') //this should be here, so it gets the correct id field
            ->join('token_user', 'users.id', '=', 'token_user.user_id')
            ->join('tokens', 'tokens.id', '=', 'token_user.token_id')
            ->where('tokens.type', '=', Token::TYPE_ACTIVATION)
            ->where('tokens.token', '=', $token);
            //first() //not do this here!!! nor get()

    }


    /**
     * Returns the user who has the password reset token indicated
     * @param  [type] $query [description]
     * @param string $token The password reset token
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWherePasswordResetToken($query, $token) {
        return $query
            ->select('users.*') //this should be here, so it gets the correct id field
            ->join('token_user', 'users.id', '=', 'token_user.user_id')
            ->join('tokens', 'tokens.id', '=', 'token_user.token_id')
            ->where('tokens.type', '=', Token::TYPE_PASS_RESET)
            ->where('tokens.token', '=', $token);
    }


    /** 
     * Returns the current activation token of the user
     * @return Token
     */
    public function getActivationToken() {
        return $this->tokens()
            ->where('type', '=', Token::TYPE_ACTIVATION)
            ->orderBy('created_at', 'desc')
            ->first();
    }


    /** 
     * Returns the current password reset token of the user
     * @return Token
     */
    public function getPasswordResetToken() {
        return $this->tokens()
            ->where('type', '=', Token::TYPE_PASS_RESET)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Checks if this user is allowedto login. Currently must be active 
     * and not blocked to be able to login
     * 
     * @return boolean True if it can login, false otherwise.
     */
    public function isLoginAllowed() {
        if (!$this->active) {
            return false;
        }
        if ($this->password === null) {
            return false;
        }
        if ($this->blocked) {
            return false;
        }        
        return true;
    }


    /**
     * Checks if activation is allowed. Currently it must be not blocked to do so.
     * 
     * @return boolean True if it can be active, false otherwise.
     */
    public function isRequestActivationAllowed() {
        if ($this->active) {         
            return false;
        }
        if ($this->blocked) {
            return false;
        } 
        return true;
    }


    /**
     * Checks if the password can be reseted
     * 
     * @return boolean True if the password can be reseted, false otherwise.
     */
    public function isRequestPasswordResetAllowed() {
        if ($this->blocked) {
            return false;
        }
        if (!$this->active) {
            return false;
        }
        return true;
    }


    /**
     * Returns the message describing the login allowed status
     * 
     * @return string The message
     */
    public function getLoginAllowedMessage() {
        if (!$this->active) {
            return "The user account is not active yet!";
        }
        if ($this->password === null) {
            return 'The user has no password! The account has not been properly activated!';
        }
        if ($this->blocked) {
            return "The user account is blocked!";
        }
        return null;
    }


    /**
     * Performs the necessary actions and updates after a successful login. Currently:
     * - Updates the last login timestamp
     */
    public function doLoginActions() {
        Log::info("Updating last login date...");
        $this->authenticated_at = new Carbon();
        if (!$this->saveUpdate()) {
            Log::warning("Couldn't update last login date!");
        }
    }


    /**
     * Returns a string representing the status of password
     * @return string Password status
     */
    public function getPasswordStatus() {
        if ($this->password == null) {
            return "Password Not Set";
        } else {
            return "Password Set";
        }
    }


    /**
     * Returns a string representing the status of account activation
     * @return string Activation status
     */
    public function getActiveStatus() {
        if ($this->active) {
            return "Activated";
        } else {
            return "Deactivated";
        }
    }    


    /**
     * Returns a string representing the blocked status of this account
     * @return [string  Blocked status
     */
    public function getBlockedStatus() {
        if ($this->blocked) {
            return "Blocked (Can't login)";
        } else {
            return "Not blocked (Can login)";
        }
    }


    /**
     * Returns the quantity of days since the last password update
     *
     * @return integer # days since last pass update
     */
    public function daysSinceLastPassUpdate() {
        $date = new Carbon($this->last_pass_update_at);
        $now = new Carbon();
        return $date->diffInDays($now);
    }


    /**
     * Returns the validation rules to use for an update.
     * @return array Validation rules
     */
    public function getRulesForUpdate() {
        //clone the rules array
        $rules = array_merge(array(), self::$rules);
        $rules['email'] .= ',email,' . $this->id;
        return $rules;
    }


    /**
     * Validates the current User instance, but skiping it in the unique constraint
     * @return boolean Whether validation passes or not
     */
    public function validateUpdate() {
        return $this->validate($this->getRulesForUpdate());
    }


    /**
     * Saves the User when updating, taking into cosideration to use the
     * update rules
     * @return boolean Wheter it updated successfully or not
     */
    public function saveUpdate() {
        return $this->save($this->getRulesForUpdate());
    }


    /**
     * Blocks the current user
     * @return void
     */
    public function block() {
        //Log::info('block() - Current blocked value: ' . $this->blocked);
        if (!$this->blocked) {
            //Log::info('Blocking!');
            $this->blocked = true;
            $this->saveUpdate();
        }
    }


    /**
     * Unblocks the current user
     * @return void
     */
    public function unblock() {
        //Log::info('unblock() - Current blocked value: ' . $this->blocked);
        if ($this->blocked) {
            //Log::info('Unblocking!');
            $this->blocked = false;
            $this->saveUpdate();
        }
    }


    /**
     * Vaidates if the current user can be activated with the
     * data provided. 
     * It validates that the token provided matches the current one, and also 
     * that the hours passed since the generation of the actual token is less 
     * than $ttlHours.
     * 
     * @param string $token The token that must be matched against the actual in the user's records
     * @param int $ttlHours Time to live in hours of the current token.
     * @return boolean True if the user can be activated, false otherwise.
     */
    public function isActivateAllowed($token, $ttlHours = 24) {

        if (!$this->isRequestActivationAllowed()) {
            return false;
        }

        //check the token
        $activationToken = $this->getActivationToken();
        if ($token !== $activationToken->token) {
            //Log::info('Token is different: ' . $token . ' <> ' . $this->activation_token);
            return false;
        }

        //validate time to live
        $now = new Carbon();
        $tokenTime = new Carbon($activationToken->created_at); //@todo: change this login to start using the expires_at field
        $diffHours = $now->diffInHours($tokenTime);
        if ($diffHours > $ttlHours) {
            //Log::info('Time to live expired! Diff: ' . $diffHours);
            return false;
        }

        return true;

    }


    /**
     * Validates if the password reset is allowed, based on token and time to live
     * 
     * @param string $token Token to validate
     * @param string $email The email to check agains the actual login email of the user
     * @param integer $ttlHours time to live to check agains the create_at date
     * @return boolean True if can reset password
     */
    public function isPasswordResetAllowed($token, $email = null, $ttlHours = 24) {

        if (!$this->isRequestPasswordResetAllowed()) 
        {
            //Log::info("A");
            return false;
        }

        //check email
        if ($email) 
        {
            if ($email !== $this->email) 
            {
                //Log::info("B: " . $email . " " . $this->email);
                return false;
            }
        }

        //check token
        $passResetToken = $this->getPasswordResetToken();
        if ($token !== $passResetToken->token) 
        {
            //Log::info("C");
            return false;
        }

        //validate time to live
        $now = new Carbon();
        $tokenTime = new Carbon($passResetToken->created_at);
        $diffHours = $now->diffInHours($tokenTime);
        if ($diffHours > $ttlHours) 
        {  
            //Log::info("D");          
            return false;
        }

        return true;

    }


    /**
     * Validates the given $password against the User password rules
     * 
     * @param string $password Plain text password to validate
     * @param string $passwordConfirmation Password confirmation. If not passed, it is assumed is equal to $password.
     * @return boolean True if the password is ok.
     */
    /*public function validatePassword($password, $passwordConfirmation = null) {

        if ($passwordConfirmation === null) {
            $passwordConfirmation = $password;
        }

        $this->

        return $this->validate(self::$passwordRules);

    }*/


    /**
     * Activates the user. Must provide the activation token and a new password.
     * It validates the activation first.
     * The password is Hashed prior to set it to the user.
     *
     * @param string $token The token that must be matched against the actual in the user's records
     * @param string $newPassword The password in plain text to set to the user
     * @param int $ttlHours Time to live in hours of the current token.
     * @return boolean True if the user has been activated, false otherwise.
     */
    public function activate($token, $newPassword, $ttlHours = 24) 
    {

        //validate activation
        if (!$this->isActivateAllowed($token, $ttlHours)) 
        {
            return false;
        }

        //TODO: validate password when is decided how to handle it
        /*if (!$this->validatePassword($newPassword)) {
            return false;
        }*/       

        $activationToken = $this->getActivationToken();
        if ($activationToken) 
        {
            //everything ok, activate account:        
            $this->active = true;
            $this->activated_at = new Carbon();
            $this->password = Hash::make($newPassword);
            $this->password_updated_at = new Carbon();
            $this->saveUpdate();
            return true;
        } 
        else
        {
            return false;
        }        

    }


    /**
     * Deactivates the user
     * @return void
     */
    public function deactivate() {
        if ($this->active) {
            $this->active = false;
            $this->activated_at = new Carbon();
            $this->saveUpdate();
        }
    }


    /**
     * It changes the password of the user to the one indicated.
     * It hashes the password, updates the timestamp and saves the
     * info in the user record.
     *
     * @param string $token The token to validate against the reset password token
     * @param string $email the email to validate agains the current one
     * @param string $newPlainPassword New password to hash and set for this user
     * @param int $ttlHours Time to live in hours to validate against the timestamp
     * @return boolean True if the password was set corretly, false otherwise
     */
    public function resetPassword($token, $email, $newPlainPassword, $ttlHours = 24) {

        //validate if reset is allowed
        if (!$this->isPasswordResetAllowed($token, $email, $ttlHours)) {
            return false;
        }

        $this->password = Hash::make($newPlainPassword);
        $this->password_updated_at = new Carbon();
        $this->saveUpdate();

        return true;

    }

}
