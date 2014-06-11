<?php namespace Esensi\Core\Models;

use \Carbon\Carbon;
use \Esensi\Core\Contracts\EncryptingModelInterface;
use \Esensi\Core\Contracts\HashingModelInterface;
use \Esensi\Core\Contracts\PurgingModelInterface;
use \Esensi\Core\Contracts\RelatingModelInterface;
use \Esensi\Core\Contracts\ValidatingModelInterface;
use \Esensi\Core\Traits\EncryptingModelTrait;
use \Esensi\Core\Traits\HashingModelTrait;
use \Esensi\Core\Traits\PurgingModelTrait;
use \Esensi\Core\Traits\RelatingModelTrait;
use \Esensi\Core\Traits\ValidatingModelTrait;
use \Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Support\Facades\Lang;

/**
 * Default base model
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Illuminate\Database\Eloquent\Model
 * @see \Esensi\Core\Contracts\EncryptingModelInterface
 * @see \Esensi\Core\Contracts\HashingModelInterface
 * @see \Esensi\Core\Contracts\PurgingModelInterface
 * @see \Esensi\Core\Contracts\RelatingModelInterface
 * @see \Esensi\Core\Contracts\ValidatingModelInterface
 */
class Model extends Eloquent implements
    EncryptingModelInterface,
    HashingModelInterface,
    PurgingModelInterface,
    RelatingModelInterface,
    ValidatingModelInterface {

    /**
     * Make model encrypt attributes
     *
     * @see \Esensi\Core\Traits\EncryptingModelTrait
     */
    use EncryptingModelTrait;

    /**
     * Make model hash attributes
     *
     * @see \Esensi\Core\Traits\HashingModelTrait
     */
    use HashingModelTrait;

    /**
     * Make model purge attributes
     *
     * @see \Esensi\Core\Traits\PurgingModelTrait
     */
    use PurgingModelTrait;

    /**
     * Make model use properties for model relationships
     *
     * @see \Esensi\Core\Traits\RelatingModelTrait
     */
    use RelatingModelTrait;

    /**
     * Make model validate attributes
     *
     * @see \Esensi\Core\Traits\ValidatingModelTrait
     */
    use ValidatingModelTrait;

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
     * The attribute rules that model will validate against
     *
     * @var array
     */
    public $rules = [];

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
     * Relationships that model should set up
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * Whether the model should inject it's identifier to the unique
     * validation rules before attempting validation.
     *
     * @var boolean
     */
    protected $injectIdentifier = true;

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
     * Dynamically call methods
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call( $method, $parameters )
    {
        // Dynamically call the relationship
        if ( $this->isRelationship( $method ) )
        {
            return $this->callRelationship( $method );
        }

        // Default Eloquent dynamic caller
        return parent::__call($method, $parameters);
    }

    /**
     * Dynamically retrieve attributes
     *
     * @param  string $key
     * @return mixed
     */
    public function __get( $key )
    {
        // Dynamically get the relationship
        if ( $this->isRelationship( $key ) )
        {
            // Load the relationship if not yet loaded
            if ( ! array_key_exists( $key, $this->getRelations() ) )
            {
                $relation = $this->callRelationships($key);

                // Cache the relationship for later
                $this->setRelation( $key, $relation->getResults() );
            }

            // Reuse loaded relationship
            return $this->getRelation( $key );
        }

        // Default Eloquent dynamic getter
        return parent::__get($key);
    }

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
