<?php namespace Esensi\Core\Contracts;

/**
 * Trashable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface TrashableRepositoryInterface{

    /**
     * Read the specified resource from storage even if trashed.
     *
     * @param integer $id of resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return object
     */
    public function retrieve($id);

    /**
     * Hide the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function trash($id);

    /**
     * Restore the specified resource to storage.
     *
     * @param integer $id of resource to recover
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function restore($id);

    /**
     * Remove all trashed resources from storage.
     *
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function purge();

    /**
     * Restore all trashed resources from storage.
     *
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function recover();

}