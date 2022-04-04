<?php

namespace Esensi\Core\Contracts;

/**
 * API Ancestry Controller Interface
 *
 */
interface ApiAncestryControllerInterface
{
    /**
     * Get the API ancestor controller class
     * of the current controller class.
     *
     * @return \Esensi\Core\Http\Apis\Api
     */
    public function api();

}
