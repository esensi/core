<?php

namespace Alba\User\Models;

use Ardent;
use Illuminate\Support\Facades\Log;

class Name extends Ardent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_names';

    
    /**
     * The attribute rules that Ardent will validate against
     * 
     * FIXME: find a way to not repeat the basic rules here!!!
     * 
     * @var array
     */
    public static $rules = [
        'title' => 'max:10',
        'first_name' => 'required|max:100',
        'middle_name' => 'max:100',
        'last_name' => 'required|max:100',
        'suffix' => 'max:10',
        'user' => 'exists:users'
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


    public function user() {
        return $this->belongsTo('Alba\User\Models\User');
    }


    /**
     * Validates name with basic ruleset
     * @return boolean Whether the validation succedeed or not
     */
    public function validateBasic() {
        //remove the user constraint on validation rules
        $newRules = array_merge(array(), self::$rules);
        unset($newRules['user']);
        return $this->validate($newRules);
    }


    /**
     * Returns a string with the full name of the user
     * 
     * @param  string $format Format pattern. Added for future use.
     * @return string         Full name of the user
     */
    public function getFullName($format = null) {
        
        if ($format == null) {
            $format = 'T F M L S';
        }

        $str = "";

        //Log::info('format = ' . $format);
        $formatArr = preg_split('/ /', $format);
        //Log::info(print_r($formatArr, true));
        foreach ($formatArr as $key => $value) {
            //Log::info($value);
            switch ($value) {
                case 'T':
                    $str .= ($this->title ? $this->title . ' ' : '');
                    break;
                case 'F':
                    $str .= $this->first_name . ' ';
                    break;
                case 'M':
                    $str .= ($this->middle_name ? $this->middle_name . ' ' : '');
                    break;
                case 'L':
                    $str .= $this->last_name . ' ';
                    break;
                case 'S':
                    $str .= ($this->suffix ? $this->suffix . ' ' : '');
                    break;
            }

        }
        
        return trim($str);
    }

}