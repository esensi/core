<?php namespace Esensi\Core\Contracts;

/**
 * Trashable repository interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface TrashableRepositoryInterface{

    /**
     * Read the specified resource from storage even if trashed.
     *
     * @param integer $id of resource
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function retrieve($id);

    /**
     * Hide the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function trash($id);

    /**
     * Restore the specified resource to storage.
     *
     * @param integer $id of resource to recover
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function restore($id);

    /**
     * Remove all trashed resources from storage.
     *
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function purge();

    /**
     * Restore all trashed resources from storage.
     *
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return boolean
     */
    public function recover();

}