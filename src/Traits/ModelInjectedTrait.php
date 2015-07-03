<?php

namespace Esensi\Core\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait implementation of model injection interface.
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Esensi\Core\Contracts\ModelInjectedInterface
 */
trait ModelInjectedTrait
{
    /**
     * Injected models.
     *
     * @var array of \Esensi\Core\Models\Model
     */
    protected $models = [];

    /**
     * Get the specified model by name.
     *
     * @param string $name (optional) of model
     * @return Esensi\Core\Models\Model
     */
    public function getModel( $name = null )
    {
        $name = is_null( $name ) ? $this->package : $name;
        return $this->models[ $name ];
    }

    /**
     * Set the specified model by name/
     *
     * @param \Esensi\Core\Models\Model $model
     * @param string $name (optional) of model
     * @return void
     */
    public function setModel( Model $model, $name = null )
    {
        $name = is_null( $name ) ? $this->package : $name;
        $this->models[ $name ] = $model;
    }

}
