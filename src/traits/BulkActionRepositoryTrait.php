<?php namespace Esensi\Core\Traits;

use \Esensi\Models\Collection;
use \Esensi\Support\Facades\DB;

/**
 * Trait implementation of bulk action repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\BulkActionRepositoryInterface
 */
trait BulkActionRepositoryTrait {

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return integer count of actions performed
     */
    protected function bulkAction($action, $ids)
    {
        // Get a collection of resources
        $collection = Collection::parseMixed($ids);

        // Run in a transaction for all or nothing behavior
        DB::transaction(function() use ($collection, $action)
        {
            // Iterate over the resources performing the action on each
            $collection->each(function($id)
            {
                // Repository@<action>($id)
                call_user_func_array([$this, $action], [$id]);
            });
        });

        return $collection->count();
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return integer
     */
    public function bulkDelete($ids)
    {
        return $this->bulkAction('delete', $ids);
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return integer
     */
    public function bulkRestore($ids)
    {
        return $this->bulkAction('restore', $ids);
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return integer
     */
    public function bulkTrash($ids)
    {
        return $this->bulkAction('trash', $ids);
    }

}
