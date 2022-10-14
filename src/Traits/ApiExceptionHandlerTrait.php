<?php

namespace Esensi\Core\Traits;

use Throwable;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

/**
 * Trait that handles redirects for API controllers.
 *
 * @see Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait ApiExceptionHandlerTrait
{
    /**
     * Handles exceptions for API output.
     *
     * @param  Throwable  $exception
     * @return array
     */
    public function handleException(Throwable $exception)
    {
        $data = Request::all();
        $errors = $exception->getErrors();
        $message = $exception->getMessage();
        $code = $exception->getCode() ? $exception->getCode() : 400;
        $content = array_filter(compact('errors', 'message', 'code', 'data'));
        return Response::json($content, $code);
    }

}
