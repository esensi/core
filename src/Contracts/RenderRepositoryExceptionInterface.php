<?php

namespace Esensi\Core\Contracts;

use App\Exceptions\RepositoryException;

/**
 * Render Repository Exception Interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface RenderRepositoryExceptionInterface
{
    /**
     * Render a Repository Exception into an HTTP respons.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Exceptions\RepositoryException $e
     * @return \Illuminate\Http\Response
     */
    public function renderRepositoryException($request, RepositoryException $e);

}
