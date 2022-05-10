<?php

namespace Esensi\Core\Contracts;

use Exception;

/**
 * Render Error Exception Interface
 *
 */
interface RenderErrorExceptionInterface
{
    /**
     * Render a Repository Exception into an HTTP respons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function renderErrorException($request, Exception $e);

}
