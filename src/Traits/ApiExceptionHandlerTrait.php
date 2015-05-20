<?php namespace Esensi\Core\Traits;

use Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

/**
 * Trait that handles redirects for API controllers.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait ApiExceptionHandlerTrait {

    /**
     * Handles exceptions for API output.
     *
     * @param Exception $exception
     * @return array
     */
    public function handleException(Exception $exception)
    {
        $data    = Input::all();
        $errors  = $exception->getErrors();
        $message = $exception->getMessage();
        $code    = $exception->getCode() ? $exception->getCode() : 400;
        $content = array_filter(compact('errors', 'message', 'code', 'data'));
        return Response::json($content, $code);
    }

}
