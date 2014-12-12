<?php namespace Esensi\Core\Contracts;

/**
 * API Ancestry Controller Interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface ApiAncestryControllerInterface{

    /**
     * Get the API ancestor controller class
     * of the current controller class.
     *
     * @return \Esensi\Core\Controllers\ApiController
     */
    public function api();

}
