<?php

namespace Esensi\Core\Traits;

use Throwable;

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
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function renderErrorException($request, Throwable $e)
    {
        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        $statusCode = 500;
        $view = config("esensi/core::core.views.public.{$statusCode}");

        if (view()->exists($view)) {
            return response()->view($view, [], $statusCode);
        }
    }

}
