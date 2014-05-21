<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of CRUD repository
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\CruddableRepositoryInterface
 */
trait CruddableRepositoryTrait{

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes to store on the resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return object
     */
    public function create(array $attributes)
    {
        // Create a new resource
        $model = $this->getModel();
        $object =  new $model;

        // Fill the resource attributes
        $object->fill($attributes);

        // Throw an error if the resource could not be updated
        if( ! $object->save() )
        {
            $this->throwException( $object->errors(), $this->error('create') );
        }

        return $object;
    }

    /**
     * Read the specified resource from storage.
     *
     * @param integer $id of resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return object
     */
    public function read(integer $id)
    {
        // Get the resource
        $object = $this->getModel()->find($id);

        // Throw an error if the resource could not be found
        if( ! $object )
        {
            $this->throwException( $this->error('read') );
        }

        return $object;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @param array $attributes to update on the resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return object
     */
    public function update(integer $id, array $attributes)
    {
        // Get the resource
        $object =  $this->read($id);

        // Fill the resource attributes
        $object->fill($attributes);

        // Throw an error if the resource could not be updated
        if( ! $object->save() )
        {
            $this->throwException( $object->errors(), $this->error('update') );
        }

        return $object;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function delete(integer $id)
    {
        // Get the resource
        $object = $this->read($id);
        
        // Force deletes on soft-deleted models
        if( method_exists( $object, 'isSoftDeleting' ) && $object->isSoftDeleting() )
        {
            $result = $object->forceDelete();
        }

        // Delete regular models
        else
        {
            $result = $object->delete();
        }

        // Throw an exception if resource could not be deleted
        if( $result === false )
        {
            $this->throwException( $this->error('delete') );
        }

        return $result;
    }

}