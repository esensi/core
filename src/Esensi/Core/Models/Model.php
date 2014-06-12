<?php namespace Esensi\Core\Models;

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
use \Illuminate\Support\Str;

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
     * Relationships that the model should set up
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
            // Use the relationship already loaded
            if ( array_key_exists( $key, $this->getRelations() ) )
            {
                return $this->getRelation( $key );
            }

            return $this->getRelationshipFromMethod($key, camel_case($key));
        }

        // Dynamically get the decrypted attributes
        if ( $this->isEncryptable( $key ) )
        {
            // Decrypt only encrypted values
            if( $this->isEncrypted( $key ) )
            {
                return $this->getEncryptedAttribute( $key );
            }
        }

        // Dynamically get time since attributes
        $normalized = strtolower(snake_case( $key ));
        $attribute = str_replace(['time_since_', 'time_till_'], ['', ''], $normalized);
        if ( ( Str::startsWith( $normalized, 'time_since_' ) || Str::startsWith( $normalized, 'time_till_' ) )
            && in_array( $attribute . '_at', $this->getDates() ) )
        {
            // Convert the attribute to a Carbon date
            $value = $this->getAttributeFromArray( $attribute . '_at');

            // Show label if date has not been set
            if( is_null($value) )
            {
                // Load the language files because Laravel doesn't seem to have loaded them by now
                Lang::addNamespace('esensi', __DIR__ . '/../../../lang');
                return Lang::get('esensi::core.labels.never_' . $attribute);
            }

            // Show human readable date
            $date = $this->asDateTime( $value );
            return $date->diffForHumans();
        }

        // Default Eloquent dynamic getter
        return parent::__get( $key );
    }

    /**
     * Dynamically set attributes
     *
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     */
    public function __set( $key, $value )
    {
        // Dynamically set the encrypted attributes
        if ( $this->isEncryptable( $key ) )
        {
            // Encrypt only decrypted values
            if ( $this->isDecrypted( $key ) )
            {
                return $this->setEncryptingAttribute( $key, $value );
            }
        }

        // Default Eloquent dynamic setter
        return parent::__set( $key, $value );
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
