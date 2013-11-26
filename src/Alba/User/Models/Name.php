<?php namespace Alba\User\Models;

use LaravelBook\Ardent\Ardent;
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
     * @var array
     */
    public static $rules = [
        'title' => ['max:10'],
        'first_name' => ['required', 'max:100'],
        'middle_name' => ['max:100'],
        'last_name' => ['required', 'max:100'],
        'suffix' => ['max:10'],
        'user' => ['exists:users']
    ];

    /**
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = ['title', 'first_name', 'middle_name', 'last_name', 'suffix'];

    /**
     * Subset of $rules' keys
     *
     * @var array
     */
    public static $rulesForNameOnly = ['title', 'first_name', 'middle_name', 'last_name', 'suffix'];

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
     * Relationships that Ardent should set up
     * 
     * @var array
     */
    public static $relationsData = array(
        'user'  => array(self::BELONGS_TO, 'Alba\User\Models\User'),
    );

    /**
     * Rules needed for storing
     * 
     * @return array
     */
    public function getRulesForStoringAttribute()
    {
        return self::$rules;
    }

    /**
     * Rules needed for the name only
     * 
     * @return array
     */
    public function getRulesForNameOnlyAttribute()
    {
        return array_only(self::$rules, self::$rulesForNameOnly);
    }

    /**
     * Get the user's name in format
     * 
     * @param  string $format
     * @return string
     */
    public function formatName($format = 'F L')
    {
        
        $str = "";

        $formatArr = preg_split('/[^a-zA-Z]/', $format);

        foreach ($formatArr as $key => $value) {

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
                    $str .= ($this->suffix ? $this->suffix : '');
                    break;
            }

        }
        
        return trim($str);
    }

    /**
     * Returns a string with the extended name of the user
     * 
     * @return string
     */
    public function getExtendedNameAttribute()
    {
        return $this->formatName('T F M L S');
    }

    /**
     * Returns a string with the full name of the user
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->formatName('F M L');
    }

}