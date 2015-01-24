<?php namespace Esensi\Core\Contracts;

use Exception;

/**
 * Exception Handler Interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface ExceptionHandlerInterface{

    /**
     * Handles exceptions with redirect
     *
     * @param Exception $exception
     * @return mixed
     */
    public function handleException(Exception $exception);

}
