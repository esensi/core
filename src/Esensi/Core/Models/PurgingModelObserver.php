<?php namespace Esensi\Core\Models;

/**
 * Model observer for PurgingModelTrait
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Traits\PurgingModelTrait
 */
class PurgingModelObserver {

    /**
     * Register an event listener for the saving event.
     * Listener purges the purgeable attributes before save.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function saving( $model )
    {
        if( $model->getPurging() )
        {
            $model->purgeAttributes();
        }
    }

}
