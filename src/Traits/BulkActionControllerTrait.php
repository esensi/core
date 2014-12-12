<?php namespace Esensi\Core\Traits;

use \Illuminate\Support\Facades\Input;
use \BadMethodCallException;

/**
 * Trait implementation of bulk action controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\BulkActionControllerInterface
 */
trait BulkActionControllerTrait {

    /**
     * Perform a bulk action on an array of resources.
     *
     * @param string $action
     * @return \Illuminate\Routing\Redirector
     * @throws BadMethodCallException
     */
    public function bulkAction($action)
    {
        // Get the bulk action to be called
        $class = get_called_class();
        $method = 'bulk' . ucfirst(studly_case($action));

        // Handle missing bulk actions
        if( ! method_exists($class, $method) )
        {
            throw new BadMethodCallException('Method ' . $method . ' does not exist on called class '. $class . '.');
        }

        // Call the bulk action and pass in the resource's IDs
        $ids = Input::get('ids', []);
        $response = call_user_func_array([$class, $method], $ids);

        // Redirect back with message
        return $response;
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return \Illuminate\Routing\Redirector
     */
    public function bulkDelete($ids)
    {
        // Use the parent API to bulk delete the resources
        $count = $this->api()->bulkDelete($ids);

        // Redirect back with message
        return $this->back( 'bulk.deleted' )
            ->with('message', $this->message('bulk.deleted', [ 'count' => $count ]) );
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return \Illuminate\Routing\Redirector
     */
    public function bulkRestore($ids)
    {
        // Use the parent API to bulk restore the resources
        $count = $this->api()->bulkRestore($ids);

        // Redirect back with message
        return $this->back( 'bulk.restored' )
            ->with('message', $this->message('bulk.restored', [ 'count' => $count ]) );
    }

    /**
     * Bulk delete the specified resources in storage.
     *
     * @param string|array $ids
     * @return \Illuminate\Routing\Redirector
     */
    public function bulkTrash($ids)
    {
        // Use the parent API to bulk trash the resources
        $count = $this->api()->bulkTrash($ids);

        // Redirect back with message
        return $this->back( 'bulk.trashed' )
            ->with('message', $this->message('bulk.trashed', [ 'count' => $count ]) );
    }

}
