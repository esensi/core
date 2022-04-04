<?php

namespace Esensi\Core\Contracts;

use App\Exceptions\RepositoryException;

/**
 * Render Repository Exception Interface
 *
 */
interface RenderRepositoryExceptionInterface
{
    /**
     * Render a Repository Exception into an HTTP respons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exceptions\RepositoryException  $e
     * @return \Illuminate\Http\Response
     */
    public function renderRepositoryException($request, RepositoryException $e);

}
