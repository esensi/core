<?php

namespace Esensi\Core\Contracts;

/**
 * Bulk Action Controller Interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface BulkActionControllerInterface
{
    /**
     * Perform a bulk action on an array of resources.
     *
     * @param string $action
     * @return Illuminate\Routing\Redirector
     * @throws BadMethodCallException
     */
    public function bulkAction($action);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return Illuminate\Routing\Redirector
     */
    public function bulkDelete($ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return Illuminate\Routing\Redirector
     */
    public function bulkRestore($ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return Illuminate\Routing\Redirector
     */
    public function bulkTrash($ids);

}
