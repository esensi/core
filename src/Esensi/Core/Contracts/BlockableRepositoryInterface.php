<?php namespace Esensi\Core\Contracts;

/**
 * Blockable Repository Interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface BlockableRepositoryInterface {

    /**
     * Set the blocked status to true for resource
     *
     * @param integer $id of resource to block
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function block($id);

    /**
     * Set the blocked status to false for resource
     *
     * @param integer $id of resource to unblock
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function unblock($id);

}
