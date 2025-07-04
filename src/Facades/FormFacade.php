<?php

namespace Esensi\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Esensi\Core\Extensions\FormBuilder
 */
class FormFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}