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
     * Inject dependencies
     *
     * @return void
     */
	public function __construct()
	{
		$this->model = new Eloquent;
	}

	/**
	 * Get the resource model used
	 *
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param array $params to overload
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function index($params = [])
	{
		return $this->model->all();
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
		if(!$this->model->fill($attributes)->save($rules))
		{
			$this->throwException(Lang::get('alba::resource.failed.store', ['message' => implode(' ', $this->model->errors()->all()) ]));
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
		if(!$object->fill($attributes)->save($rules))
		{
			$this->throwException(Lang::get('alba::resource.failed.update', ['message' => implode(' ', $object->errors()->all()) ]));
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