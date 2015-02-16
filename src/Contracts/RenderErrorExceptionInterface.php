<?php namespace Esensi\Core\Contracts;

use ErrorException;

/**
 * Render Error Exception Interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface RenderErrorExceptionInterface {

    /**
     * Render a Repository Exception into an HTTP respons.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \ErrorException $e
     * @return \Illuminate\Http\Response
     */
    public function renderErrorException($request, ErrorException $e);

}
