<?php

namespace Esensi\Core\Contracts;

use Throwable;

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
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function renderErrorException($request, Throwable $e);

}
