<?php

namespace Esensi\Core\Traits;

use Throwable;

/**
 * Trait that permits the repository to throw a custom exception.
 *
 * @see Esensi\Core\Contracts\ExceptionalRepositoryInterface
 */
trait ExceptionalRepositoryTrait
{
    /**
     * The exception to be thrown.
     *
     * @var Esensi\Core\Exceptions\RepositoryException
     */
    protected $exception = 'App\Exceptions\RepositoryException';

    /**
     * Throw an exception for this repository.
     *
     * @param  mixed  $bag
     * @param  string  $message
     * @param  long  $code
     * @param  Throwable  $previous exception
     * @return void
     */
    public function throwException($bag, $message = null, $code = 400, Throwable $previous = null)
    {
        $exception = $this->exception;
        throw new $exception($bag, $message, $code, $previous);
    }

}
