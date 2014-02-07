<?php namespace Alba\User\Resources;

use Illuminate\Support\Facades\Config;

/**
 * Custom exception handler for PermissionsResource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Exceptions\ResourceException
 */
class PermissionsResourceException extends \AlbaCoreResourceException {}

/**
 * Roles Resource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Resources\Resource
 */
class PermissionsResource extends \AlbaCoreResource {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'permission';

    /**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = 'Alba\User\Resources\PermissionsResourceException';

    /**
     * The default attributes for searching
     * 
     * @var array $defaults
     */
    protected $defaults = [
        'order' => 'display_name',
        'sort' => 'asc',
        'max' => 25,
    ];

    /**
     * Inject dependencies
     *
     * @var Alba\User\Models\Permission $model
     * @return RolesResource;
     */
    public function __construct(\AlbaPermission $model)
    {
        $this->setModel($model);
        $this->setDefaults($this->defaults);
    }

    /**
     * Show the specificed resource by the name
     *
     * @param string $name address
     * @return Model
     */
    public function showByName($name)
    {
        $object = $this->getModel()->whereName($name)->first();
        if(!$object)
        {
            $this->throwException($this->language('errors.show_by_name'));
        }
        return $object;
    }

    /**
     * Return an array of permissions
     *
     * @return array
     */
    public function names()
    {
        $ttl = Config::get('alba::permission.ttl.names', 10);
        return $this->getModel()->whereNotNull('name')->distinct()->remember($ttl)->lists('name');
    }
}