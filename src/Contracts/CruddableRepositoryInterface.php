<?php

namespace Esensi\Core\Contracts;

/**
 * CRUD-based repository interface
 *
 */
interface CruddableRepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $attributes to store on the resource
     * @return Esensi\Core\Models\Model
     */
    public function create(array $attributes);

    /**
     * Read the specified resource from storage.
     *
     * @param  integer  $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function read($id);

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
    public function delete($id);

}
