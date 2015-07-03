<?php

namespace Esensi\Core\Contracts;

/**
 * API Ancestry Controller Interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface ApiAncestryControllerInterface
{
    /**
     * Get the API ancestor controller class
     * of the current controller class.
     *
     * @return Esensi\Core\Http\Controllers\ApiController
     */
    public function api();

}
