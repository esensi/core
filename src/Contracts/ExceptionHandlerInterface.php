<?php

namespace Esensi\Core\Contracts;

use Exception;

/**
 * Exception Handler Interface
 *
 */
interface ExceptionHandlerInterface
{
    /**
     * Handles exceptions with redirect
     *
     * @param  Exception  $exception
     * @return mixed
     */
    public function handleException(Exception $exception);

}
