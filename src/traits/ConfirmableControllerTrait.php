<?php namespace Esensi\Core\Traits;

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
    protected $trashableActions = [ 'restore', 'delete' ];

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

            $data = [ $this->package => $object ];
        }

        // Render confirmation modal
        $this->modal( $action . '_confirm', $data );
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
        $callable = str_replace('Confirm', '', $method);

        // Re-route call to confirm() method
        if( substr($method, -7, 7) == 'Confirm' && method_exists($this, $callable) )
        {
            $action = snake_case($callable);
            array_unshift($parameters, $action);
            return call_user_func_array([ $this, 'confirm' ], $parameters);
        }

        // Fall back to parent handler
        return parent::__call($method, $parameters);
    }

}