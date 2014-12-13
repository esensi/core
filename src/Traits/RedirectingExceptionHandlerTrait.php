<?php namespace Esensi\Core\Traits;

use \EsensiCoreRepositoryException as RepositoryException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * Trait that handles redirects using redirects.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait RedirectingExceptionHandlerTrait {

    /**
     * Handles exceptions with redirect.
     *
     * @param \Esensi\Core\Exceptions\RepositoryException $exception
     * @return Illuminate\Routing\Redirector
     */
    public function handleException(RepositoryException $exception)
    {
        // Get redirect
        $referer = Request::header('referer');
        $redirect = empty($referer) ? Redirect::route('users.signin') : Redirect::back();

        // Send redirect
        return $redirect->with('message', $exception->getMessage())
            ->with('code', $exception->getCode())
            ->withErrors($exception->getBag())
            ->withInput();
    }

}
