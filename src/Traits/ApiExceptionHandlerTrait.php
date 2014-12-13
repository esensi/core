<?php namespace Esensi\Core\Traits;

use \EsensiCoreRepositoryException as RepositoryException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

/**
 * Trait that handles redirects for API controllers.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait ApiExceptionHandlerTrait {

    /**
     * Handles exceptions for API output.
     *
     * @param RepositoryException $exception
     * @return array
     */
    protected function handleException(RepositoryException $exception)
    {
        $data    = Input::all();
        $errors  = $exception->getErrors();
        $message = $exception->getMessage();
        $code    = $exception->getCode() ? $exception->getCode() : 400;
        $content = array_filter(compact('errors', 'message', 'code', 'data'));
        return Response::json($content, $code);
    }

}
