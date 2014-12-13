<?php namespace Esensi\Core\Contracts;

use EsensiCoreRepositoryException as RepositoryException;

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
     * @param \Esensi\Core\Exceptions\RepositoryException $exception
     * @return mixed
     */
    public function handleException(RepositoryException $exception);

}