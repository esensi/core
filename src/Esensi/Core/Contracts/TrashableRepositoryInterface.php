<?php namespace Esensi\Core\Contracts;

use \Esensi\Core\Contracts\CruddableRepositoryInterface;

/**
 * Trashable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\CruddableRepositoryInterface
 */
interface TrashableRepositoryInterface extends CruddableRepositoryInterface{

    /**
     * Hide the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @return boolean
     */
    public function trash(integer $id);

    /**
     * Show the specified resource in storage.
     *
     * @param integer $id of resource to recover
     * @return boolean
     */
    public function recover(integer $id);

    /**
     * Remove all trashed resources from storage.
     *
     * @return boolean
     */
    public function purge();

    /**
     * Recover all trashed resources from storage.
     *
     * @return boolean
     */
    public function restore();

}