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
     * @return \Esensi\Core\Models\Model
     */
    public function create(array $attributes)
    {
        // Fill the model attributes
        $model = $this->getModel();
        $object = new $model( array_only($attributes, $model->getFillable()) );

        // Throw an error if the resource could not be updated
        if( ! $object->save() )
        {
            $this->throwException( $object->getErrors(), $this->error('create') );
        }

        return $object;
    }

    /**
     * Read the specified resource from storage.
     *
     * @param integer $id of resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
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
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function update($id, array $attributes)
    {
        // Get the resource
        $object = $this->read( $id );
        $object->fill( array_only($attributes, $object->getFillable()) );

        // Throw an error if the resource could not be updated
        if( ! $object->save() )
        {
            $this->throwException( $object->getErrors(), $this->error('update') );
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
    public function delete($id)
    {
        // Force deletes on soft-deleted models
        if( method_exists( $this->getModel(), 'forceDelete' ) )
        {
            // Get the resource
            $object = $this->retrieve($id);

            // Forcibly delete this trashable resource
            // Use trash() if you want to soft delete it
            $result = $object->forceDelete();
        }

        // Delete regular models
        else
        {
            // Get the resource
            $object = $this->read($id);

            // Do a regular delete on this resource
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
