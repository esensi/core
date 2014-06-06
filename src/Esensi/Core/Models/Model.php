<?php namespace Esensi\Core\Models;

use \Carbon\Carbon;
use \Magniloquent\Magniloquent\Magniloquent;
use \Illuminate\Support\Facades\Lang;

/**
 * Default base model
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Illuminate\Database\Eloquent\Model
 * @see \Magniloquent\Magniloquent\Magniloquent
 */
class Model extends Magniloquent {

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
    protected static $purgeable = [];

    /**
     * Relationships that model should set up
     *
     * @var array
     */
    protected static $relationships = [];

    /**
     * The attribute rules that model will validate against
     *
     * @var array
     */
    public static $rules = [

        // Rules that apply for any type of write
        'save' => [

        ],

        // Rules that apply for creates
        'create' => [

        ],

        // Rules that apply for updates
        'update' => [

        ],
    ];

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
     * Returns the number of minutes since the creation time
     *
     * @return string
     */
    public function getTimeSinceCreatedAttribute()
    {
        // Short circuit for models that have not been created
        if( is_null($this->created_at) )
        {
            return Lang::get('esensi::core.messages.never_created');
        }

        $date = new Carbon($this->created_at);
        return $date->diffForHumans();
    }

    /**
     * Returns the number of minutes since the update time
     *
     * @return string
     */
    public function getTimeSinceUpdatedAttribute()
    {
        // Short circuit for models that have not been updated
        if( is_null($this->updated_at) )
        {
            return Lang::get('esensi::core.messages.never_updated');
        }

        $date = new Carbon($this->updated_at);
        return $date->diffForHumans();
    }

    /**
     * Returns the number of minutes since the deleted time
     *
     * @return string
     */
    public function getTimeSinceDeletedAttribute()
    {
        // Short circuit for models that have not been deleted
        if( is_null($this->deleted_at) )
        {
            return Lang::get('esensi::core.messages.never_deleted');
        }

        $date = new Carbon($this->deleted_at);
        return $date->diffForHumans();
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
