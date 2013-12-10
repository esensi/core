<?php namespace Alba\User\Models;

use Alba\User\Controllers\TokensResource;
use Alba\Core\Models\Model;
use Carbon\Carbon;

/**
 * Alba\Token model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Models\User
 */
class Token extends Model {

    /**
     * Types of tokens
     *
     * @var string
     */
    const TYPE_ACTIVATION = 'activation';

    const TYPE_PASSWORD_RESET = 'password_reset';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tokens';

    /**
     * Attributes that Eloquent will conver to Carbon dates
     *
     * @var array
     */
    protected $dateAttributes = [
        'created_at', 'expires_at',
    ];

    /**
     * Fields that can be filled
     *
     * @var boolean
     */
    protected $fillable = ['expires_at', 'type', 'token'];

    /**
     * Options for order by dropdowns
     *
     * @var array
     */
    public $orderOptions = [
        'id'                    => 'ID',
        'type'                  => 'Type',
        'token'                 => 'Token',
        'created_at'            => 'Created',
        'expires_at'            => 'Expires',
    ];

    /**
     * Enable soft deletes on model
     *
     * @var boolean
     */
    protected $softDelete = false;

    /**
     * Allow Eloquent to handle timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that can be full-text searched
     *
     * @var array
     */
    public $searchable = ['type'];

    /**
     * Relationships that Ardent should set up
     * 
     * @var array
     */
    public static $relationsData = [
        'users' => [self::BELONGS_TO_MANY, 'Alba\User\Models\User'],
    ];

    /**
     * The attribute rules that Ardent will validate against
     * 
     * @var array
     */
    public static $rules = [
        'token' => ['required', 'max:256'],
        'type' => ['required', 'max:32'],
        'expires_at' => ['required', 'date'],
    ];

    /**
     * Subset of $rules' keys for storing
     * 
     * @var array
     */
    public static $rulesForStoring = ['token', 'type', 'expires_at'];

    /**
     * Subset of $rules' keys for updating
     * 
     * @var array
     */
    public static $rulesForUpdating = ['token', 'type', 'expires_at'];

    /**
     * Rules needed for storing
     * 
     * @return array
     */
    public function getRulesForStoringAttribute()
    {
        return array_only(self::$rules, self::$rulesForStoring);
    }

    /**
     * Rules needed for updating
     * 
     * @return array
     */
    public function getRulesForUpdatingAttribute()
    {
        return array_only(self::$rules, self::$rulesForUpdating);
    }

    /**
     * Returns the number of minutes since the update time
     *
     * @return string
     */
    public function getTimeTillExpiresAttribute()
    {
        // Short circuit for models that have not had an expiration set
        if( is_null($this->expires_at) )
        {
            return Lang::get('alba::core.messages.never_expires');
        }

        $date = new Carbon($this->expires_at);
        return $date->diffForHumans();
    }

    /**
     * Returns the URL for the model
     *
     * @return string
     */
    public function getRouteAttribute()
    {
        $resource = new TokensResource($this);
        return $resource->route($this->type, $this->token);
    }
   
    /**
     * Stuff to do before saving the model
     *
     * @return void
     */
    public function beforeSave()
    {
        if ( is_null($this->created_at) )
        {
            $this->created_at = Carbon::now();
        }
    }

    /**
     * Builds a query scope to return tokens of a certain type
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string|array $types of tokens
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfType($query, $types)
    {
        // Convert types string to array
        if ( is_string($types) )
            $types = explode(',', $types);

        // Query the tokens table for matching types
        return $query->whereIn('type', $types);
    }
}