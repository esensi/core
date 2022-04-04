<?php

namespace Esensi\Core\Traits;

use App\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Trait implementation of bulk action repository interface.
 *
 * @see Esensi\Core\Contracts\BulkActionRepositoryInterface
 */
trait BulkActionRepositoryTrait
{
    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string  $action to perform
     * @param  string|array  $ids
     * @return integer count of actions performed
     */
    public function bulkAction($action, $ids)
    {
        // Get a collection of resources
        $collection = Collection::parseMixed($ids, [',', '+', ' ']);

        // Get any extra arguments passed
        $arguments = array_slice(func_get_args(), 2);

        // Run in a transaction for all or nothing behavior
        DB::transaction(function() use ($collection, $action, $arguments)
        {
            // Iterate over the resources performing the action on each
            $collection->each(function($id) use ($action, $arguments) {
                // Repository@<action>($id)
                array_unshift($arguments, $id);
                call_user_func_array([$this, studly_case($action)], $arguments);
            });
        });

        // Fire bulk after event listeners
        $this->eventFire('bulk.' . snake_case($action), $collection->all());

        return $collection->count();
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string|array  $ids
     * @return integer
     */
    public function bulkDelete($ids)
    {
        return $this->bulkAction('delete', $ids);
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string|array  $ids
     * @return integer
     */
    public function bulkRestore($ids)
    {
        return $this->bulkAction('restore', $ids);
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param  string|array  $ids
     * @return integer
     */
    public function bulkTrash($ids)
    {
        return $this->bulkAction('trash', $ids);
    }

}
