<?php

namespace Esensi\Core\Traits;

/**
 * Trait implementation of CRUD repository.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\CruddableRepositoryInterface
 */
trait CruddableRepositoryTrait
{
    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes to store on the resource
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function create(array $attributes)
    {
        // Fill the model attributes
        $model = $this->getModel();
        $object = new $model( array_only($attributes, $model->getFillable()) );

        // Fire before listeners
        $this->eventUntil('creating', [ $object ] );

        // Throw an error if the resource could not be updated
        if( ! $object->save() )
        {
            $this->throwException( $object->getErrors(), $this->error('create') );
        }

        // Fire after listeners
        $this->eventFire('created', [ $object ] );

        return $object;
    }

    /**
     * Read the specified resource from storage.
     *
     * @param integer $id of resource
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function read($id)
    {
        // Get the resource
        $object = $this->getModel()
            ->find( $id );

        // Throw an error if the resource could not be found
        if( ! $object )
        {
            $this->throwException( $this->error('read'), null, 404);
        }

        return $object;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @param array $attributes to update on the resource
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function update($id, array $attributes)
    {
        // Get the resource
        $object = $this->read( $id );

        // Fire before listeners
        $this->eventUntil('updating', [ $object ] );

        // Fill the attributes
        $object->fill( array_only($attributes, $object->getFillable()) );

        // Throw an error if the resource could not be updated
        if( ! $object->save() )
        {
            $this->throwException( $object->getErrors(), $this->error('update') );
        }

        // Fire after listeners
        $this->eventFire('updated', [ $object ] );

        return $object;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function delete($id)
    {
        // Force deletes on soft-deleted models
        if( method_exists( $this->getModel(), 'restore' ) )
        {
            // Get the resource
            $object = $this->retrieve($id);

            // Fire before listeners
            $this->eventUntil('deleting', [ $object ] );

            // Forcibly delete this trashable resource
            // Use trash() if you want to soft delete it
            $object->forceDelete();

            // Returning true to enforce method contract because
            // Laravel's forceDelete() currently returns void
            $result = true;
        }

        // Delete regular models
        else
        {
            // Get the resource
            $object = $this->read($id);

            // Fire before listeners
            $this->eventUntil('deleting', [ $object ] );

            // Do a regular delete on this resource
            // Result is always going to have a bool value here, as we are
            // working with an existing model at this point.
            $result = $object->delete();
        }

        // Throw an exception if resource could not be deleted
        if( $result === false )
        {
            $this->throwException( $this->error('delete') );
        }

        // Fire after listeners
        $this->eventFire('deleted', [ $object ] );

        return $result;
    }

}
