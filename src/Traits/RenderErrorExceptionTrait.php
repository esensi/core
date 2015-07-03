<?php

namespace Esensi\Core\Traits;

use ErrorException;

/**
 * Trait that renders ErrorExceptions
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\RenderErrorExceptionInterface
 */
trait RenderErrorExceptionTrait
{
    /**
     * Render an error exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \ErrorException  $e
     * @return \Illuminate\Http\Response
     */
    public function renderErrorException($request, ErrorException $e)
    {
        // Skip custom error views when in debug mode
        if( config('app.debug') )
        {
            return parent::render($request, $e);
        }

        // Render as an opaque 500 internal server error
        $status = 500;
        $line = 'esensi/core::core.views.public.' . $status;
        $view = config($line);
        if( view()->exists($view) )
        {
            return response()->view($view, [], $status);
        }
    }

}
