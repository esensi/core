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
        $response = null;

        if (!config('app.debug')) {
            $statusCode = 500;
            $view = config("esensi/core::core.views.public.{$statusCode}");

            if (view()->exists($view)) {
                $response = response()->view($view, [], $statusCode);
            }
        }

        if (is_null($response)) {
            $response = parent::render($request, $e);
        }

        return $response;
    }

}
