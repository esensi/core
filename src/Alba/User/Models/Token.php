<?php namespace Alba\User\Models;

use Ardent;

use Carbon\Carbon;

use Illuminate\Support\Facades\Log;


class Token extends Ardent {

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

    
    protected $fillable = ['expires_at', 'type', 'token'];


    public $timestamps = false;


    public static $rulesForToken = [
        'token' => ['required', 'max:256']
    ];

    public static $rulesForType = [        
        'type' => ['required', 'max:32']
    ];

    
    public function getRulesForStoringAttribute()
    {
        return array_merge(self::$rulesForToken, self::$rulesForType);
    }

    public function getRulesForTokenAttribute()
    {
        return self::$rulesForToken;
    }
   


    public function beforeSave()
    {      

        if ($this->created_at == null) {
            $this->created_at = new Carbon();
        }

    }



    /**
     * Override of the save method to set the corrent rules if none are
     * passed to the save method
     * 
     * @see Ardent::save
     */
    //Problems with $customMessages = null... throws exception of not being an array in Ardent
    /*public function save(array $rules = null, array $customMessages = null, 
        array $options = null, \Closure $beforeSave = null, \Closure $afterSave = null) 
    {
        if ($rules == null)
        {
            $rules = $this->rulesForStoring;
        }
        return parent::save($rules, $customMessages, $options, $beforeSave, $afterSave);
    }*/

    

}