<?php namespace Esensi\Core\Models;

/**
 * Model observer for HashingModelTrait
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Traits\HashingModelTrait
 */
class HashingModelObserver {

    /**
     * Register an event listener for the creating event.
     * Listener hashes the hashable attributes before save.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function creating( $model )
    {
        $this->performHashing( $model, 'creating' );
    }

    /**
     * Register an event listener for the updating event.
     * Listener hashes the hashable attributes before save.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function updating( $model )
    {
        $this->performHashing( $model, 'updating' );
    }

    /**
     * Check if hashing is enabled and then hash the attributes
     * that need hashing.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event name
     * @return void
     */
    protected function performHashing( $model, $event = 'saving')
    {
        if( $model->getHashing() )
        {
            $model->hashAttributes();
        }
    }

}
