<?php namespace Alba\Core\Controllers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Alba\Core\Contracts\ResourceInterface;
use Alba\Core\Controllers\Controller;
use Alba\Core\Exceptions\ResourceException;

class Resource extends Controller implements ResourceInterface {

	/**
     * The resource model
     * 
     * @var Illuminate\Database\Eloquent\Model;
     */
    protected $model;

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
		$this->model = new Eloquent;
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
		if( !empty($this->keyword) )
		{
			$keyword = $this->keyword;
			$fields = $this->model->searchable;
			$this->where = function( $query ) use ($keyword, $fields)
	            {
	                foreach($fields as $field)
	                {
						$query->orWhere($field, 'LIKE', '%' . $keyword . '%');
	                }
	            };
		}

		// Build new query with loaded relationships
		$query = $this->model->newQuery();
		if ( isset($this->relationships) )
			$query->with($this->relationships);
		
		// Build up the query using scope closures
		if ( isset($this->scopes) && !empty($this->scopes) )
		{
			foreach( $this->scopes as $scope => $args)
			{
				call_user_func_array([$query, $scope], $args);
			}
		}

		// Paginate the results
		return $query->where($this->where)
			->orderBy($this->order, $this->sort)
			->paginate($this->max);
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
		$this->model->fill($attributes);
		if(!$this->model->save($rules))
		{
			$this->throwException($object->errors(), Lang::get('alba::resource.failed.store'));
		}
		return $this->model;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id of object
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function show($id)
	{
		$object = $this->model->find($id);
		if(!$object)
		{
			$this->throwException(Lang::get('alba::resource.failed.show'));
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
			$this->throwException($object->errors(), Lang::get('alba::resource.failed.update'));
		}
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
		$object = $this->show($id);
		
		$result = ($force) ? $object->forceDelete() : $object->delete();
		
		if(!$result)
		{
			$this->throwException(Lang::get('alba::resource.failed.destroy'));
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
	public function throwException($messageBag = null, $message = null, $code = 0, Exception $previous = null)
	{
		throw new $this->exception($messageBag, $message, $code, $previous);
	}
}