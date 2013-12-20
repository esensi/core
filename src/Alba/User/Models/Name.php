<?php namespace Alba\User\Models;

use LaravelBook\Ardent\Ardent;
use Illuminate\Support\Facades\Log;

/**
 * Alba\Name model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Models\User
 */
class Name extends Ardent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_names';

    /**
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = ['title', 'first_name', 'middle_name', 'last_name', 'suffix'];

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
        'user' => ['exists:users'],
    ];

    /**
     * Subset of $rules' keys for storing
     *
     * @var array
     */
    public static $rulesForStoring = ['title', 'first_name', 'middle_name', 'last_name', 'suffix'];

    /**
     * Subset of $rules' keys for updating
     *
     * @var array
     */
    public static $rulesForUpdating = ['title', 'first_name', 'middle_name', 'last_name', 'suffix'];

    /**
     * Relationships that Ardent should set up
     * 
     * @var array
     */
    public static $relationsData = [
        'user'  => [self::BELONGS_TO, '\AlbaUser'],
    ];

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
     * Builds a query scope to return titles alphabetically for a dropdown list
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $key to use in returned array
     * @param string $sort direction
     * @return array [$key => $name]
     */
    public function scopeListTitles($query, $key = null, $sort = 'asc')
    {
        return $query->distinct()->whereNotNull('title')->orderBy('title', $sort)->lists('title', $key, $sort);
    }

    /**
     * Builds a query scope to return suffixes alphabetically for a dropdown list
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $key to use in returned array
     * @param string $sort direction
     * @return array [$key => $name]
     */
    public function scopeListSuffixes($query, $key = null, $sort = 'asc')
    {
        return $query->distinct()->whereNotNull('suffix')->orderBy('suffix', $sort)->lists('suffix', $key, $sort);
    }
}