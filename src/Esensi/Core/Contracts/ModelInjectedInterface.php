<?php namespace Esensi\Core\Contracts;

use \Illuminate\Database\Eloquent\Model;

/**
 * Interface for injecting models into a class
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface ModelInjectedInterface{

    /**
     * Get the specified model by name
     *
     * @param string $name (optional) of model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel( string $name = null );

    /**
     * Set the specified model by name
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $name (optional) of model
     * @return void
     */
    public function setModel( Model $model, string $name = null );

}