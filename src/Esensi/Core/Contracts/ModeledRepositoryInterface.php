<?php namespace Esensi\Core\Contracts;

interface ModeledRepositoryInterface{

    /**
     * Get the specified model by name
     *
     * @param string $name (optional) of model
     * @return Model
     * 
     */
    public function getModel( string $name = null );

    /**
     * Set the specified model by name
     *
     * @param Model $model
     * @param string $name (optional) of model
     * @return void
     * 
     */
    public function setModel( Model $model, string $name = null );

}