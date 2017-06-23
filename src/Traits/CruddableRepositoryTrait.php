<?php

namespace Esensi\Core\Traits;

use Esensi\Core\Contracts\ResettableModelInterface;
use Esensi\Core\Models\Model;

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
     * Cache of model instances
     *
     * @var Esensi\Core\Models\Model[]
     */
    protected $modelInstances = [];

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
        $object = new $model;
        if( $object instanceof ResettableModelInterface ) {
            $object->resetAttributes();
        }
        $object->fill(array_only($attributes, $model->getFillable()));

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
     * @param integer|Esensi\Core\Models\Model $id of resource or instance
     * @param boolean                          $refresh force loading a fresh copy of resource from the DB
     *
     * @throws Esensi\Core\Exceptions\RepositoryException
     *
     * @return Esensi\Core\Models\Model
     */
    public function read($id, $refresh = false)
    {
        if ($id instanceof Model) {
            // We already have a model, pass it straight through
            return $id;
        }

        // Look for instance of model in our local cache
        $object = array_get($this->modelInstances, $id);

        if ( ! is_null($object) && ! $refresh) {
            // Model's already been loaded from DB, no need to query it again
            return $object;
        }

        // Get the resource
        $object = $this->getModel()
            ->find( $id );

        // Throw an error if the resource could not be found
        if( ! $object )
        {
            $this->throwException( $this->error('read'), null, 404);
        }

        $this->modelInstances[$id] = $object;

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
        if( $object instanceof ResettableModelInterface ) {
            $object->resetAttributes();
            $object->fill( array_filter(array_only($attributes, $object->getFillable())) );
        } else {
            $object->fill( array_only($attributes, $object->getFillable()) );
        }

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
