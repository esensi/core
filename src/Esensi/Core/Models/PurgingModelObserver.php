<?php namespace Esensi\Core\Models;

/**
 * Model observer for PurgingModelTrait
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Traits\PurgingModelTrait
 */
class PurgingModelObserver {

    /**
     * Register an event listener for the creating event.
     * Listener purgees the purgeable attributes before save.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function creating( $model )
    {
        $this->performPurging( $model, 'creating' );
    }

    /**
     * Register an event listener for the updating event.
     * Listener purgees the purgeable attributes before save.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function updating( $model )
    {
        $this->performPurging( $model, 'updating' );
    }

    /**
     * Check if purging is enabled and then purge the attributes
     * that need purging.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event name
     * @return void
     */
    protected function performPurging( $model, $event = 'saving')
    {
        if( $model->getPurging() )
        {
            $model->purgeAttributes();
        }
    }

}
