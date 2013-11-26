<?php namespace Alba\User\Models;

use Ardent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Token extends Ardent {

    /**
     * Types of tokens
     *
     * @var string
     */
    const TYPE_ACTIVATION = 'activation';

    const TYPE_PASS_RESET = 'pass_reset';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tokens';

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
     * Fields that can be filled
     *
     * @var boolean
     */
    protected $fillable = ['expires_at', 'type', 'token'];

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
     * Rules needed for storing
     * 
     * @return array
     */
    public function getRulesForStoringAttribute()
    {
        return array_only(self::$rules, self::$rulesForStoring);
    }
   
    /**
     * Stuff to do before saving the model
     *
     * @return void
     */
    public function beforeSave()
    {
        if ($this->created_at == null)
        {
            $this->created_at = Carbon::now();
        }
    }

}