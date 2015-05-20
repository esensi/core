<?php namespace Esensi\Core\Contracts;

use Exception;

/**
 * Exception throwing repository interface
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface ExceptionalRepositoryInterface{

    /**
     * Throw an exception for this repository
     *
     * @param mixed $bag
     * @param string $message
     * @param long $code
     * @param Exception $previous exception
     * @return void
     */
    public function throwException($bag, $message = null, $code = 0, Exception $previous = null);

}
