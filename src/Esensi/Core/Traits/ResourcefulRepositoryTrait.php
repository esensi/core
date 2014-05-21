<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of a resource repository
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ResourcefulRepositoryInterface
 */
trait ResourcefulRepositoryTrait{

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
    public function show($id)
    {
        return $this->read($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->delete($id);
    }

}