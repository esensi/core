<?php namespace Esensi\Core\Models;

/**
 * Model observer for HashingModelTrait
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Traits\HashingModelTrait
 */
class HashingModelObserver {

    /**
     * Register an event listener for the saving event.
     * Listener hashes the hashable attributes before save.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function saving( $model )
    {
        if( $model->getHashing() )
        {
            $model->hashAttributes();
        }
    }

}
