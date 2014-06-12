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
     * @return bool
     */
    public function restoring( $model )
    {
        return $this->performValidation( $model, 'restoring' );
    }

}
