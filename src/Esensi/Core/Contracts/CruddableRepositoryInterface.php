<?php namespace Esensi\Core\Contracts;

/**
 * CRUD-based repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface CruddableRepositoryInterface{

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes to store on the resource
     * @return object
     */
    public function create(array $attributes);

    /**
     * Read the specified resource from storage.
     *
     * @param integer $id of resource
     * @return object
     */
    public function read(integer $id);

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @param array $attributes to update on the resource
     * @return object
     */
    public function update(integer $id, array $attributes);

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return boolean
     */
    public function delete(integer $id);

}