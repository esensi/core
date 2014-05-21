<?php namespace Esensi\Core\Traits;

use \Exception;
use \Esensi\Core\Exceptions\RepositoryException;

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
    protected function handleException(RepositoryException $exception)
    {
        $errors = $exception->getErrors();
        $message = $exception->getMessage();
        return compact('errors', 'message');
    }

}