<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Traits\TrashableRepositoryTrait;
use \Esensi\Core\Traits\FilterableRepositoryTrait;

/**
 * Trait implementation of a resource repository
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ResourcefulRepositoryInterface
 * @see \Esensi\Core\Traits\TrashableRepositoryTrait
 * @see \Esensi\Core\Traits\CruddableRepositoryTrait
 * @see \Esensi\Core\Traits\ModeledRepositoryTrait
 * @see \Esensi\Core\Traits\FilterableRepositoryTrait
 */
trait ResourcefulRepositoryTrait{

    /**
     * Make this repository perform trashing operations
     *
     * @see \Esensi\Core\Traits\TrashableRepositoryTrait
     * @see \Esensi\Core\Traits\CruddableRepositoryTrait
     * @see \Esensi\Core\Traits\ModeledRepositoryTrait
     */
    use TrashableRepositoryTrait;

    /**
     * Make this repository use filters and pagination
     *
     * @see \Esensi\Core\Traits\FilterableRepositoryTrait
     * @see \Esensi\Core\Traits\ModeledRepositoryTrait
     */
    use FilterableRepositoryTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        // Get a new query builder
        $query = $this->getModel()->newQuery();

        // Filter the resources
        $this->filter($query);

        // Paginate the resources
        return $this->paginate($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes to store on the resource
     * @return object
     */
    public function store(array $attributes)
    {
        return $this->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return object
     */
    public function show(integer $id)
    {
        return $this->read($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @param array $attributes to update on the resource
     * @return object
     */
    public function update(integer $id, array $attributes)
    {
        $this->update($id, $attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return boolean
     */
    public function destroy(integer $id)
    {
        return $this->delete($id);
    }

}