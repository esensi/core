<?php

namespace Esensi\Core\Traits;

use Exception;

/**
 * Trait that renders ErrorExceptions
 *
 * @see Esensi\Core\Contracts\RenderErrorExceptionInterface
 */
trait RenderErrorExceptionTrait
{
    /**
     * Render an error exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function renderErrorException($request, Exception $e)
    {
        // Skip custom error views when in debug mode
        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        // Render as an opaque 500 internal server error
        $status = 500;
        $line = 'esensi/core::core.views.public.' . $status;
        $view = config($line);
        if (view()->exists($view)) {
            return response()->view($view, [], $status);
        }
    }

}
