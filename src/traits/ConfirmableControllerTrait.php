<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Models\Collection;
use \Illuminate\Support\Facades\Route;

/**
 * Trait implementation of confirmable controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ConfirmableControllerInterface
 */
trait ConfirmableControllerTrait {

    /**
     * Actions that require using retrieve instead of restore
     *
     * @var array
     */
    protected $trashableActions = [ 'restore', 'delete', 'bulkRestore', 'bulkDelete' ];

    /**
     * Display a confirmation modal for the specified resource action.
     *
     * @param string $action
     * @param integer $id (optional)
     * @return void
     */
    public function confirm($action, $id = null)
    {
        $data = [];

        // Get the object from the parent API
        if( ! is_null($id) )
        {
            // Get the resource out of the trash
            if( in_array($action, $this->trashableActions)
                && method_exists($this->getRepository(), 'retrieve') )
            {
                $object = parent::retrieve($id);
            }

            // Get the resource from the parent API
            else
            {
                $object = parent::show($id);
            }

            // Pass obkect under the singular package named variable to the view
            $data = [ $this->package => $object, 'id' => $id ];
        }

        // Render confirmation modal
        $this->modal( $action . '_confirm', $data );
    }

    /**
     * Display a confirmation modal for the specified resource bulk action.
     *
     * @param string $action
     * @param string|array $ids (optional)
     * @return void
     */
    public function confirmBulk($action, $ids = null)
    {
        $data = [];
        $inTrash = false;

        // Get the objects from the repository
        if( ! is_null($ids) )
        {
            $ids = Collection::parseMixed($ids, [',', '+', ' '])->all();

            // Get the resource out of the trash
            if( in_array($action, $this->trashableActions)
                && method_exists($this->getRepository(), 'retrieve') )
            {
                $inTrash = true;
            }

            // Get the resources as a collection
            $collection = $this->getRepository()
                ->findIn('id', $ids);

            // Pass collection under the plural package named variable to the view
            $data = [ str_plural($this->package) => $collection, 'ids' => $ids ];
        }

        // Render confirmation modal
        $view = str_replace('bulk_', 'bulk.', $action) . '_confirm';
        $this->modal( $view, $data );
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // Determine confirmation action
        $callable = lcfirst(studly_case(str_replace('Confirm', '', $method)));

        // Re-route call to a confirmation method
        if( substr($method, -7, 7) == 'Confirm' && method_exists($this, $callable) )
        {
            // Determine confirmation method to use: confirmBulk() or confirm()
            $isBulk = substr($method, 0, 4) == 'bulk';
            $confirm = $isBulk ? 'confirmBulk' : 'confirm';

            // Prepare action and parameters to pass to confirm method
            $action = $isBulk ? snake_case(Route::getParameter('action')) : snake_case($callable);
            array_unshift($parameters, $action);

            // Call the confirmation method
            return call_user_func_array([ $this, $confirm ], $parameters);
        }

        // Fall back to parent handler
        return parent::__call($method, $parameters);
    }

}
