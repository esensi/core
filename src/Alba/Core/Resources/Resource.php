<?php namespace Alba\Core\Resources;

use LaravelBook\Ardent\Ardent;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;

/**
 * Core Resource controller as the base for all module Resource controllers
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class Resource extends \AlbaCoreController implements \AlbaCoreResourceInterface {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'core';

	/**
     * This is maintained for backwards compatibility.
     * It should be removed by 0.2.x release.
     * 
     * @var \Alba\Core\Models\Model
     */
    protected $model;

	/**
     * Injected models
     * 
     * @var array
     */
    protected $models = [];

	/**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = '\AlbaCoreResourceException';
    
	/**
     * The default attributes for searching
     * 
     * @var array $defaults
     */
    protected $defaults = [
    	'order' => 'id',
    	'sort' => 'asc',
    	'max' => 25,
    ];

    /**
     * Inject dependencies
     *
     * @return void
     */
	public function __construct(\AlbaCoreModel $model)
	{
		$this->setModel($model);
		$this->setDefaults($this->defaults);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param array $params to overload
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function index($params = [])
	{
		// Overload the defaults with params
		if ( !empty($params) )
			$this->setDefaults($params);

		// Add full-text query on Model::$searchable columns
		if( !empty($this->keywords) )
		{
			$keywords = $this->keywords;
			if(is_string($keywords))
				$keywords = explode(',', $keywords);
			$fields = $this->getModel()->searchable;
			$this->where = function( $query ) use ($keywords, $fields)
	            {
	                foreach($fields as $field)
	                {
						foreach($keywords as $keyword)
						{
							$query->orWhere($field, 'LIKE', '%' . $keyword . '%');
						}
	                }
	            };
		}

		// Build new query with loaded relationships
		$query = $this->getModel()->newQuery()->select([$this->getModel()->getTable().'.*']);
		if ( isset($this->relationships) )
			$query->with($this->relationships);
		
		// Include trashed results if model supports it
		if ( $this->getModel()->isSoftDeleting() && isset($this->trashed) )
		{
			switch($this->trashed)
			{
				case 'only':
					$query->onlyTrashed();

				case '1':
				case 'true':
					$query->withTrashed();
			}
		}

		// Build up the query using scope closures
		if ( isset($this->scopes) && !empty($this->scopes) )
		{
			foreach( $this->scopes as $scope => $args)
			{
				call_user_func_array([$query, $scope], $args);
			}
		}

		// Paginate the results
		$paginator = $query->where($this->where)
			->orderBy($this->order, $this->sort)
			->paginate($this->max);
			
		// Generate paginated links
		$queries = array_except($this->defaults, ['relationships', 'scopes', 'where']);
		return $paginator->appends($queries);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param array $attributes to fill on the object
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function store($attributes)
	{
		$rules = $this->getModel()->rulesForStoring;
        $object = new $this->getModel();
		$object->fill($attributes);
		if(!$object->save($rules))
		{
			$this->throwException($object->errors(), $this->language('errors.store'));
		}
		return $object;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id of object
	 * @param boolean $withTrashed
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function show($id, $withTrashed = false)
	{
		$excludeTrashed = !$withTrashed;
		$object = $this->getModel()->newQuery($excludeTrashed)->find($id);
		if(!$object)
		{
			$this->throwException($this->language('errors.show'));
		}
		return $object;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id of object to update
	 * @param array $attributes to fill on the object
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function update($id, $attributes)
	{
		$object = $this->show($id);

		$rules = $object->rulesForUpdating;
		$object->fill($attributes);
		if(!$object->save($rules))
		{
			$this->throwException($object->errors(), $this->language('errors.update'));
		}
		return $object;
	}

	/**
	 * Restore the specified resource after being soft deleted
	 *
	 * @param int $id of object to restore
	 * @return bool
	 * 
	 */
	public function restore($id)
	{
		$object = $this->show($id, true);
		
		// Sloppy way to get around Ardent $rules validation
		// @todo add a restore() method to Ardent that uses the forceSave method
		$rules = $object::$rules;
		$object::$rules = [];

		// Restore user
		if(!$object->restore())
		{
			$this->throwException($this->language('errors.restore'));
		}
		$object::$rules = $rules;
		return $object;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id of object to remove
	 * @param bool $force delete
	 * @return bool
	 * 
	 */
	public function destroy($id, $force = true)
	{
		$object = $this->show($id, true);
		
		// Check if trashing is allowed
		if( method_exists($object, 'isTrashingAllowed') )
		{
			if(!$object->isTrashingAllowed())
			{
				$this->throwException($this->language('errors.trashing'));
			}
		}

		// Delete the user
		$result = ($force || $object->trashed()) ? $object->forceDelete() : $object->delete();
		if($result === false)
		{
			$this->throwException($this->language('errors.destroy'));
		}

		return $result;
	}

	/**
	 * Set the default attributes for searching
	 *
	 * @param array $defaults
	 * @return void
	 */
	protected function setDefaults(array $defaults)
	{
		// Overload the attributes
		foreach( $defaults as $default => $value )
		{
			// Only assign values that exist
			if( $value )
			{
				$this->{$default} = $value;
				$this->defaults[$default] = $value;
			}

			// Otherwise assign the existing default
			elseif ( isset($this->defaults[$default]) )
			{
				$this->{$default} = $this->defaults[$default];
			}
		}

		// Makes sure $this->where is a closure
		if ( !isset($this->where) )
			$this->where = function(){};

		// Make sure the sort order is lowercase
		$this->sort = strtolower($this->sort);
	}
	
	/**
	 * Throw an exception for this resource
	 *
	 * @param mixed $messageBag
	 * @param string $message
	 * @param long $code
	 * @param Exception $previous exception
	 * @return void
	 */
	public function throwException($messageBag, $message = null, $code = 0, Exception $previous = null)
	{
		$exceptionName = $this->exception;
		throw new $exceptionName($messageBag, $message, $code, $previous);
	}

    /**
     * Get a language line
     *
     * @param string $key to language config
     * @param array $replacements in language line
     * @return string
     */
    protected function language($key, $replacements = [])
    {
        $key = str_singular($this->module) . '.' .$key;
        return Lang::has('alba::' . $key) ? Lang::get('alba::' . $key, $replacements) : Lang::get($key, $replacements);
    }

    /**
     * Get the specified model by name
     *
     * @param string $name of model
     * @return Ardent
     * 
     */
    public function getModel($name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
        }

        // Provided for backwards compatibility
        // @todo remove by 0.2.x
        return isset($this->models[$name]) ? $this->models[$name] : $this->{$name};
    }

    /**
     * Set the specified model by name
     *
     * @param Ardent $model
     * @param string $name of model
     * @return Ardent
     * 
     */
    public function setModel(Ardent $model, $name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
            
            // Provided for backwards compatibility
        	// @todo remove by 0.2.x
        	$this->model = $model;
        }

        return $this->models[$name] = $model;
    }
}