<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of blockable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\BlockableRepositoryInterface
 */
trait BlockableRepositoryTrait {

    /**
     * Set the blocked status to true for resource
     *
     * @param integer $id of resource to block
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function block($id)
    {
        // Get the resource
        $object = $this->find($id);

        // Make sure we can block
        if( ! $object->isBlockingAllowed() )
        {
            $this->throwException($this->error('blocking_not_allowed'));
        }

        // Block
        $object->blocked = 1;

        // Block the resource
        if( ! $object->save() )
        {
            $this->throwException($object->getErrors(), $this->error('block'));
        }

        return $object;
    }

    /**
     * Set the blocked status to false for resource
     *
     * @param integer $id of resource to unblock
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function unblock($id)
    {
        // Get the resource
        $object = $this->find($id);

        // Make sure we can unblock
        if( ! $object->isUnblockingAllowed() )
        {
            $this->throwException($this->error('unblocking_not_allowed'));
        }

        // Unblock
        $object->blocked = 0;

        // Unblock the resource
        if( ! $object->save() )
        {
            $this->throwException($object->getErrors(), $this->error('unblock'));
        }

        return $object;
    }

}
