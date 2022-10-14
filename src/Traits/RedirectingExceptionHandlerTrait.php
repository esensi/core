<?php

namespace Esensi\Core\Traits;

use ThrowExceptionable;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * Trait that handles redirects using redirects.
 *
 * @see Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait RedirectingExceptionHandlerTrait
{
    /**
     * Handles exceptions with redirect.
     *
     * @param  Exception  $exception
     * @return Illuminate\Routing\Redirector
     */
    public function handleException(Exception $exception)
    {
        // Get redirect
        $referer = Request::header('referer');
        $url = empty($referer) ? route('users.signin') : $referer;

        // Add the fragment if defined on the route
        $options = Request::route()->getAction();
        $fragment = array_get($options, 'fragment');
        $url = $fragment ? $url . '#' . $fragment : $url;

        // Send redirect
        return Redirect::to($url)
            ->with('message', $exception->getMessage())
            ->with('code', $exception->getCode())
            ->withErrors($exception->getBag())
            ->withInput();
    }

}
