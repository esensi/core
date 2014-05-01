<?php namespace Esensi\Core\Controllers;

use \Illuminate\Support\Facades\Input;

/**
 * Controller for accessing Resource as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Controllers\Controller
 * @see \Esensi\Core\Resources\Resource
 */
class ApiController extends \EsensiCoreController {

    /**
     * Inject dependencies
     *
     * @param \Esensi\Core\Resources\Resource $resource;
     * @return void
     */
    public function __construct(\EsensiCoreResource $resource)
    {
        $this->setResource($resource);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $params = Input::only('max', 'order', 'sort', 'keywords');
        return $this->getResource()->index($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $attributes = Input::all();
        return $this->getResource()->store($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of object
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->getResource()->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id of object to update
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id)
    {
        $attributes = Input::all();
        return $this->getResource()->update($id, $attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id of object to remove
     * @param bool $force delete
     * @return bool
     * 
     */
    public function destroy($id)
    {
        return $this->getResource()->destroy($id);
    }

}