<?php namespace Esensi\Core\Models;

use \Esensi\Model\Model as BaseModel;
use \Illuminate\Support\Facades\Lang;
use \Illuminate\Support\Str;

/**
 * Base Model
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Model\Model
 */
abstract class Model extends BaseModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'models';

    /**
     * The relationships that should be eager loaded with each query
     *
     * @var array
     */
    protected $with = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The rulesets that the model will validate against.
     *
     * @var array
     */
    protected $rulesets = [];

    /**
     * The attributes that can be full-text searched
     *
     * @var array
     */
    public $searchable = [];

    /**
     * The attributes to purge before saving
     *
     * @var array
     */
    protected $purgeable = [];

    /**
     * The attributes to hash before saving
     *
     * @var array
     */
    protected $hashable = [];

    /**
     * The attributes to encrypt when set and
     * decrypt when gotten
     *
     * @var array
     */
    protected $encryptable = [];

    /**
     * Relationships that the model should set up
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * Extra attributes to be added to pivot relationships.
     *
     * @var array
     */
    protected $relationshipPivots = [];

    /**
     * Whether the model should inject it's identifier to the unique
     * validation rules before attempting validation.
     *
     * @var boolean
     */
    protected $injectUniqueIdentifier = true;

    /**
     * Options for trashed status dropdowns
     *
     * @var array
     */
    public $trashedOptions = [
        1      => 'Any Trashed',
        'only' => 'Trashed',
        0      => 'Not Trashed',
    ];

    /**
     * Options for order by dropdowns
     *
     * @var array
     */
    public $orderOptions = [
        'id' => 'ID',
    ];

    /**
     * Options for sorting dropdowns
     *
     * @var array
     */
    public $sortOptions = [
        'asc'  => 'Ascending',
        'desc' => 'Descending',
    ];

    /**
     * Options for results per page dropdowns
     *
     * @var array
     */
    public $maxOptions = [
        10  => '10 Per Page',
        25  => '25 Per Page',
        50  => '50 Per Page',
        100 => '100 Per Page',
    ];

    /**
     * Dynamically retrieve attributes
     *
     * @param  string $key
     * @return mixed
     */
    public function __get( $key )
    {
        // Dynamically get time since attributes
        $normalized = Str::snake( $key );
        $attribute = str_replace(['time_since_', 'time_till_'], ['', ''], $normalized);
        if ( ( Str::startsWith( $normalized, 'time_since_' ) || Str::startsWith( $normalized, 'time_till_' ) )
            && in_array( $attribute . '_at', $this->getDates() ) )
        {
            // Convert the attribute to a Carbon date
            $value = $this->getAttributeFromArray( $attribute . '_at');

            // Show label if date has not been set
            if( is_null($value) )
            {
                return Lang::get('esensi/core::core.labels.never_' . $attribute);
            }

            // Show human readable date
            $date = $this->asDateTime( $value );
            return $date->diffForHumans();
        }

        // Default dynamic getter
        return parent::__get( $key );
    }

    /**
     * Builds a query scope to return object alphabetically for a dropdown list
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $column (optional) to order by
     * @param string $key (optional) to use in returned array
     * @param string $sort (optional) direction
     * @return array
     */
    public function scopeListAlphabetically($query, $column = null, $key = null, $sort = 'asc')
    {
        $column = is_null($column) ? $this->getKeyName() : $column;
        return $query->orderBy($column, $sort)->lists($column, $key);
    }

}
