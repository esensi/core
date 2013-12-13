<?php namespace Alba\Core\Controllers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Alba\Core\Contracts\ResourceInterface;
use Alba\Core\Controllers\Controller;
use Alba\Core\Exceptions\ResourceException;

/**
 * Core Resource controller as the base for all module Resource controllers
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class Resource extends Controller implements ResourceInterface {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'core';

	/**
     * The resource model
     * 
     * @var Illuminate\Database\Eloquent\Model;
     */
    protected $model;

	/**
     * Injected resources
     * 
     * @var array
     */
    protected $resources = [];

	/**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = 'ResourceException';
    
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
	public function __construct()
	{
        //@todo:  we should have a core model that has all of the ardent + eloquent properties on it plus the extras that resource uses 
        //so that way we can make sure to just extend it and copy over any properties we want to change
		$this->model = new Model;     

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
			$fields = $this->model->searchable;
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
		$query = $this->model->newQuery()->select([$this->model->getTable().'.*']);
		if ( isset($this->relationships) )
			$query->with($this->relationships);
		
		// Include trashed results if model supports it
		if ( $this->model->isSoftDeleting() && isset($this->trashed) )
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
		$rules = $this->model->rulesForStoring;
        $object = new $this->model;
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
		$object = $this->model->newQuery($excludeTrashed)->find($id);
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
		
		$result = ($force || $object->trashed()) ? $object->forceDelete() : $object->delete();

		if($result === false)
		{
			$this->throwException($this->language('errors.destroy'));
		}

		return $result;
	}

	/**
	 * Get the resource model used
	 *
	 * @param string $model to return
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function getModel($model = 'model')
	{
		return $this->{$model};
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
}