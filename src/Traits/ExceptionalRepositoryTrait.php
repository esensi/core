<?php namespace Esensi\Core\Traits;

use Exception;

/**
 * Trait that permits the repository to throw a custom exception.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\ExceptionalRepositoryInterface
 */
trait ExceptionalRepositoryTrait {

    /**
     * The exception to be thrown.
     *
     * @var Esensi\Core\Exceptions\RepositoryException
     */
    protected $exception = 'App\Exceptions\RepositoryException';

    /**
     * Throw an exception for this repository.
     *
     * @param mixed $bag
     * @param string $message
     * @param long $code
     * @param Exception $previous exception
     * @return void
     */
    public function throwException($bag, $message = null, $code = 400, Exception $previous = null)
    {
        $exception = $this->exception;
        throw new $exception($bag, $message, $code, $previous);
    }

}
