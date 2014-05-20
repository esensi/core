<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Traits\CruddableRepositoryTrait;

/**
 * Trait implementation of trashable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\TrashableRepositoryInterface
 * @see \Esensi\Core\Traits\CruddableRepositoryTrait
 * @see \Esensi\Core\Traits\ModeledRepositoryTrait
 */
trait TrashableRepositoryTrait{

    /**
     * Make this repository alias the CRUD methods
     *
     * @see \Esensi\Core\Traits\CruddableRepositoryTrait
     * @see \Esensi\Core\Traits\ModeledRepositoryTrait
     */
    use CruddableRepositoryTrait;

    /**
     * Hide the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @return boolean
     */
    public function trash(integer $id)
    {
        return $this->read($id)
            ->delete();
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param integer $id of resource to recover
     * @return boolean
     */
    public function restore(integer $id)
    {
        return $this->read($id)
            ->restore();
    }

    /**
     * Remove all trashed resources from storage.
     *
     * @return boolean
     */
    public function purge()
    {
        return $this->getModel()
            ->onlyTrashed()
            ->forceDelete();
    }

    /**
     * Restore all trashed resources from storage.
     *
     * @return boolean
     */
    public function recover()
    {
        return $this->getModel()
            ->onlyTrashed()
            ->restore();
    }

}