<?php namespace Alba\User\Models;

use Ardent;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Entrust\HasRole;

class User extends Ardent implements UserInterface, RemindableInterface {
    
    /**
     * The Entrust role trait
     */
    use HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['*'];

    /**
     * The attributes that can be mass assigned
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    /**
     * The attributes that are considered full-text fields
     *
     * @var array
     */
    public static $searchable = ['first_name', 'last_name', 'email'];

    /**
     * The attribute rules that Ardent will validate against
     *
     * @var array
     */
    public static $rules = [
        'first_name'        => ['required', 'max:32'],
        'last_name'         => ['required', 'max:32'],
        'email'             => ['required', 'max:128', 'email', 'unique:users'],
        'password'          => ['required', 'between:6,10'],
        'role'              => ['exists:roles,name'],
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
     * The attributes that Ardent will auto hash so they are not stored in plain text
     *
     * @var array
     */
    public static $passwordAttributes  = ['password'];
    
    /**
     * Auto hash attributes that should not be stored in plain text
     *
     * @var boolean
     */
    public $autoHashPasswordAttributes = true;

    /**
     * Enable soft deletes on model
     *
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * Relationships Ardent should setup
     *
     * @var array
     */
    public static $relationsData = [];

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }
}