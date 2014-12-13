<?php namespace Esensi\Core\Traits;

use Esensi\Core\Models\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Trait implementation of bulk action repository interface.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\BulkActionRepositoryInterface
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
        $collection = Collection::parseMixed($ids, [',', '+', ' ']);

        // Run in a transaction for all or nothing behavior
        DB::transaction(function() use ($collection, $action)
        {
            // Iterate over the resources performing the action on each
            $collection->each(function($id) use ($action)
            {
                // Repository@<action>($id)
                call_user_func_array([$this, studly_case($action)], [$id]);
            });
        });

        // Fire bulk after event listeners
        $this->eventFire('bulk.' . snake_case($action), $collection->all());

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
