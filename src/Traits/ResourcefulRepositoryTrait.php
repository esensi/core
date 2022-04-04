<?php

namespace Esensi\Core\Traits;

/**
 * Trait implementation of a resource repository.
 *
 * @see Esensi\Core\Contracts\ResourcefulRepositoryInterface
 */
trait ResourcefulRepositoryTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param  array  $filters (optional)
     * @return Illuminate\Pagination\Paginator
     */
    public function index(array $filters = [])
    {
        // Paginate the resources
        $query = $this->query($filters);
        return $this->paginate($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $attributes to store on the resource
     * @return Esensi\Core\Models\Model
     */
    public function store(array $attributes)
    {
        return $this->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|Esensi\Core\Models\Model  $id of resource or instance
     * @param boolean  $refresh force loading a fresh copy of resource from the DB
     *
     * @return Esensi\Core\Models\Model
     */
    public function show($id, $refresh = false)
    {
        return $this->read($id, $refresh);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id of resource to remove
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->delete($id);
    }

    /**
     * Remove all resources from storage.
     *
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function truncate()
    {
        // Fire before listeners
        $this->eventUntil('truncating');

        // Force delete all the resources
        $result = $this->getModel()
            ->query()
            ->delete();

        // Throw an error if resources could not be deleted
        if (! $result) {
            $this->throwException([], $this->error('truncate') );
        }

        // Fire after listeners
        $this->eventFire('truncated');

        return $result;
    }

}
