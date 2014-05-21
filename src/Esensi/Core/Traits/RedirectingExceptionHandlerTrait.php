<?php namespace Esensi\Core\Traits;

use \EsensiCoreRepositoryException as RepositoryException;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Request;

/**
 * Trait that handles redirects using redirects
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait RedirectingExceptionHandlerTrait{

    /**
     * Handles exceptions with redirect
     *
     * @param \Esensi\Core\Exceptions\RepositoryException $exception
     * @return \Illuminate\Routing\Redirector
     */
    public function handleException(RepositoryException $exception)
    {
        // Get redirect
        $referer = Request::header('referer');
        $redirect = empty($referer) ? Redirect::route('users.signin') : Redirect::back();

        // Send redirect
        return $redirect->with('message', $exception->getMessage())
            ->withErrors($exception->getBag())
            ->withInput();
    }

}