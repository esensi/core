<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of trashable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\TrashableRepositoryInterface
 */
trait TrashableRepositoryTrait{

    /**
     * Read the specified resource from storage even if trashed.
     *
     * @param integer $id of resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function retrieve($id)
    {
        // Get the resource
        $object = $this->getModel()
            ->withTrashed()
            ->find($id);

        // Throw an error if resource is not found
        if( ! $object )
        {
            $this->throwException( $this->error('retrieve') );
        }

        return $object;
    }

    /**
     * Hide the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function trash($id)
    {
        // Soft delete a resource
        // Can't trash a trashed resource
        $result = $this->read($id)
            ->delete();

        // Throw an error if resource could not be deleted
        if( ! $result )
        {
            $this->throwException( $this->error('trash') );
        }

        return $result;
    }

    /**
     * Restore the specified resource to storage.
     *
     * @todo make restoration rules be part of validation
     * @param integer $id of resource to recover
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function restore($id)
    {
        // Retrieve a trashed resource
        $object = $this->retrieve($id);

        // Make sure that the rules are not used with
        // the save() that restore() does
        $oldRules = $object::$rules;
        $object::$rules = [];

        // Restore the object
        $object->restore();

        // Make sure we re-apply the rules after the restore()
        $object::$rules = $oldRules;

        // Throw an error if resource could not be restored
        if( ! $object->isValid() )
        {
            dd($object->errors());
            $this->throwException( $object->errors(), $this->error('restore') );
        }

        return $result;
    }

    /**
     * Remove all trashed resources from storage.
     *
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function purge()
    {
        // Force delete all the trashed resources
        $result = $this->getModel()
            ->onlyTrashed()
            ->forceDelete();

        // Throw an error if resources could not be deleted
        if( ! $result )
        {
            $this->throwException( $this->error('purge') );
        }

        return $result;
    }

    /**
     * Restore all trashed resources from storage.
     *
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function recover()
    {
        // Restore all the trashed resources
        $result = $this->getModel()
            ->onlyTrashed()
            ->restore();

        // Throw an error if resources could not be restored
        if( ! $result )
        {
            $this->throwException( $this->error('recover') );
        }

        return $result;
    }

}
