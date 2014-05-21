<?php namespace Esensi\Core\Traits;

use \Illuminate\Database\Eloquent\Model;

/**
 * Trait implementation of model injection interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ModelInjectedInterface
 */
trait ModelInjectedTrait{

    /**
     * Injected models
     * 
     * @var array of \Illuminate\Database\Eloquent\Model
     */
    protected $models = [];

    /**
     * Get the specified model by name
     *
     * @param string $name (optional) of model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel( string $name = null )
    {
        $name = is_null( $name ) ? 'default' : $name;
        return $this->models[ $name ];
    }

    /**
     * Set the specified model by name
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $name (optional) of model
     * @return void
     */
    public function setModel( Model $model, string $name = null )
    {
        $name = is_null( $name ) ? 'default' : $name;
        $this->models[ $name ] = $model;
    }
}