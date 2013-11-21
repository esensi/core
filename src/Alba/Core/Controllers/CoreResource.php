<?php namespace Alba\Core\Controllers;

use Illuminate\Database\Eloquent\Model;
use Alba\Core\Contracts\ResourceInterface;
use Alba\Core\Controllers\CoreController;
use Alba\Core\Controllers\CoreResourceException;

class CoreResource extends CoreController implements ResourceInterface {

	/**
     * The resource model
     * 
     * @var Illuminate\Database\Eloquent\Model;
     */
    protected $resource;

	/**
     * The exception to be thrown
     * 
     * @var Alba\Core\Controllers\CoreResourceException;
     */
    protected $exception = 'CoreResourceException';

    /**
     * Inject dependencies
     *
     * @return void
     */
	public function __construct()
	{
		$this->resource = new Eloquent;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param array $params to overload
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function index($params = [])
	{
		return $this->resource->all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param array $attributes to fill on the object
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function store($attributes)
	{
		$rules = $this->resource->rulesForStoring;
		if(!$this->resource->fill($attributes)->save($rules))
		{
			$this->throwException('Object could not be stored: '.implode(' ', $object->errors()->all()) );
		}
		return $this->resource;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id of object
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function show($id)
	{
		$object = $this->resource->find($id);
		if(!$object)
		{
			$this->throwException('Object could not be found.');
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
		if(!$object->fill($attributes)->save($rules))
		{
			$this->throwException('Object could not be updated: '.implode(' ', $object->errors()->all()) );
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
			$this->throwException('Object could not be deleted.');
		}

		return $result;
	}

	/**
	 * Throw an exception for this resource
	 *
	 * @param string $message
	 * @param long $code
	 * @param Exception $previous exception
	 * @return void
	 */
	public function throwException($message = null, $code = 0, Exception $previous = null)
	{
		throw new $this->exception($message, $code, $previous);
	}
}