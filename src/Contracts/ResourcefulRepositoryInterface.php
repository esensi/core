<?php

namespace Esensi\Core\Contracts;

/**
 * Resourceful repository interface
 *
 */
interface ResourcefulRepositoryInterface
{
    /**
     * Display a listing of the resource.
     *
     * @param  array  $filters (optional)
     * @return Illuminate\Pagination\Paginator
     */
    public function index(array $filters = []);

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $attributes to store on the resource
     * @return Esensi\Core\Models\Model
     */
    public function store(array $attributes);

    /**
     * Display the specified resource.
     *
     * @param  integer  $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function show($id);

    /**
     * Update the specified resource in storage.
     *
     * @param  integer  $id of resource to update
     * @param  array  $attributes to update on the resource
     * @return Esensi\Core\Models\Model
     */
    public function update($id, array $attributes);

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id of resource to remove
     * @return boolean
     */
    public function destroy($id);

    /**
     * Remove all resources from storage.
     *
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function truncate();

}
