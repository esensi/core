<?php namespace Alba\User\Models;

use Ardent;
use Alba\Core\Utils\StringUtils;


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

    protected $hidden = array('*');


    public static $rules = [
        'token' => 'required',
        'type' => 'required',
    ];


    /**
     * Creates a new instance of token
     * @param string $type Token type
     */
    public function __construct($type) {
        //@todo: validate type
        $this->token = StringUtils::generateGuid(false);
        $this->type = $type;
    }


}