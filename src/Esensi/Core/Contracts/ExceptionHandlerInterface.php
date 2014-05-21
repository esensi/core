<?php namespace Esensi\Core\Contracts;

use \EsensiCoreRepositoryException as RepositoryException;

/**
 * Exception Handler Interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface ExceptionHandlerInterface{

    /**
     * Handles exceptions with redirect
     *
     * @param \Esensi\Core\Exceptions\RepositoryException $exception
     * @return mixed
     */
    public function handleException(RepositoryException $exception);

}