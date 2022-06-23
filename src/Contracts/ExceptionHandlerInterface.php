<?php

namespace Esensi\Core\Contracts;

use Throwable;

/**
 * Exception Handler Interface
 *
 */
interface ExceptionHandlerInterface
{
    /**
     * Handles exceptions with redirect
     *
     * @param  Throwable  $exception
     * @return mixed
     */
    public function handleException(Throwable $exception);

}
