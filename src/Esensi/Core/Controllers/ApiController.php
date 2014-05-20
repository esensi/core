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
     * @param \Esensi\Core\Contracts\RepositoryInterface $repository;
     * @return void
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $options = Input::only('max', 'order', 'sort', 'keywords');
        return $this->getRepository()
            ->setOptions($options)
            ->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        return $this->getRepository()
            ->store(Input::all());
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of object
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show(integer $id)
    {
        return $this->getRepository()
            ->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of object to update
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(integer $id)
    {
        return $this->getRepository()
            ->update($id, Input::all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of object to remove
     * @return boolean
     * 
     */
    public function destroy(integer $id)
    {
        return $this->getRepository()
            ->destroy($id);
    }

}