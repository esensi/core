<?php

namespace Esensi\Core\Contracts;

/**
 * Activateable Repository Interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface ActivateableRepositoryInterface
{
    /**
     * Activate the specified resource
     *
     * @param integer $id of resource
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function activate($id);

    /**
     * Set the activation status to false for resource
     *
     * @param integer $id of resource to deactivate
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function deactivate($id);

}
