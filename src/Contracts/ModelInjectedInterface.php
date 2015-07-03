<?php

namespace Esensi\Core\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface for injecting models into a class
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface ModelInjectedInterface
{
    /**
     * Get the specified model by name
     *
     * @param string $name (optional) of model
     * @return Esensi\Core\Models\Model
     */
    public function getModel( $name = null );

    /**
     * Set the specified model by name
     *
     * @param \Esensi\Core\Models\Model $model
     * @param string $name (optional) of model
     * @return void
     */
    public function setModel( Model $model, $name = null );

}
