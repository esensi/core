<?php

namespace Esensi\Core\Contracts;

/**
 * Bulk Action Repository Interface
 *
 */
interface BulkActionRepositoryInterface
{
    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string  $action to perform
     * @param  string|array  $ids
     * @return integer count of actions performed
     */
    function bulkAction($action, $ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string|array  $ids
     * @return integer
     */
    public function bulkDelete($ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string|array  $ids
     * @return integer
     */
    public function bulkRestore($ids);

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string|array  $ids
     * @return integer
     */
    public function bulkTrash($ids);

}
