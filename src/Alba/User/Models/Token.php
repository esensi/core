<?php namespace Alba\User\Models;

use Ardent;

use Carbon\Carbon;

use Illuminate\Support\Facades\Log;


class Token extends Ardent {

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


    public static $rules = [
        'token' => 'required|max:256',
        'type' => 'required|max:32',
    ];

    public $timestamps = false;


    public function beforeSave()
    {

        //It seems that validation executes first, so this is never executed
        //it would throw the validation error first
        if ($this->token == null) {
            $this->token = StringUtils::generateGuid(false);
        }

        if ($this->created_at == null) {
            $this->created_at = new Carbon();
        }

    }


    

}