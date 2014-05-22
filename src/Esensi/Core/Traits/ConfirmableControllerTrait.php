<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of confirmable controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ConfirmableControllerInterface
 */
trait ConfirmableControllerTrait {

    /**
     * Display a confirmation modal for the specified resource action.
     *
     * @param integer $id
     * @param string $action
     * @return \Illuminate\View\View
     */
    public function confirm($id, $action)
    {
        // Get the resource from parent API
        $object = parent::show($id);

        // Render confirmation modal
        $this->modal( $action . '_confirm', [ $this->package => $object ] );
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
            array_push($parameters, $action);
            return call_user_func_array([ $this, 'confirm' ], $parameters);
        }

        // Fall back to parent handler
        return parent::__call($method, $parameters);
    }

}