<?php namespace Esensi\Core\Traits;

use \EsensiCoreRepositoryException as RepositoryException;
use \Illuminate\Support\Facades\Response;

/**
 * Trait that handles redirects for API controllers
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait ApiExceptionHandlerTrait{

    /**
     * Handles exceptions for API output
     *
     * @param RepositoryException $exception
     * @return array
     */
    public function handleException(RepositoryException $exception)
    {
        $errors = $exception->getErrors();
        $message = $exception->getMessage();
        $code = $exception->getCode() ?: 400;
        $content = array_filter(compact('errors', 'message'));
        return Response::json($content, $code);
    }

}
