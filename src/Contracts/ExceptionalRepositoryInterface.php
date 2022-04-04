<?php

namespace Esensi\Core\Contracts;

use Exception;

/**
 * Exception throwing repository interface
 *
 */
interface ExceptionalRepositoryInterface
{
    /**
     * Throw an exception for this repository
     *
     * @param mixed  $bag
     * @param string  $message
     * @param long  $code
     * @param Exception  $previous exception
     * @return void
     */
    public function throwException($bag, $message = null, $code = 0, Exception $previous = null);

}
