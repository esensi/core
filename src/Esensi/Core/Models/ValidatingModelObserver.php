<?php namespace Esensi\Core\Models;

use \Watson\Validating\ValidatingObserver;

/**
 * Model observer for ValidatingModelTrait
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Traits\ValidatingModelTrait
 * @see \Watson\Validating\ValidatingObserver
 */
class ValidatingModelObserver extends ValidatingObserver{

    /**
     * Register an event listener for the restoring event.
     * Listener validates against the restoring ruleset.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function restoring( $model )
    {
        return $this->performValidation( $model, 'restoring' );
    }

    /**
     * Perform validation with the specified ruleset.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event name
     * @return boolean
     */
    protected function performValidation($model, $event)
    {
        // If the model has validating enabled, perform it.
        if ($model->getValidating() && $model->getRuleset($event))
        {
            if( $model->isValid($event) === false )
            {
                return false;
            }
        }
    }

}
