<?php

namespace Esensi\Core\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface for injecting models into a class
 *
 */
interface ModelInjectedInterface
{
    /**
     * Get the specified model by name
     *
     * @param  string  $name (optional) of model
     * @return Esensi\Core\Models\Model
     */
    public function getModel( $name = null );

    /**
     * Set the specified model by name
     *
     * @param  \Esensi\Core\Models\Model  $model
     * @param  string  $name (optional) of model
     * @return void
     */
    public function setModel( Model $model, $name = null );

}
