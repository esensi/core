<?php

namespace Esensi\Core\Contracts;

use Throwable;

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
     * @param Throwable  $previous exception
     * @return void
     */
    public function throwException($bag, $message = null, $code = 0, Throwable $previous = null);

}
