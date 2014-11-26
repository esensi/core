<?php namespace Esensi\Core\Contracts;

/**
 * Activateable Repository Interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface ActivateableRepositoryInterface {

    /**
     * Activate the specified resource
     *
     * @param integer $id of resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function activate($id);

    /**
     * Set the activation status to false for resource
     *
     * @param integer $id of resource to deactivate
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function deactivate($id);

}
