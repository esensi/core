<?php

namespace Esensi\Core\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Throwable;
use Illuminate\Support\Facades\Request;

/**
 * Trait that handles redirects using redirects.
 *
 * @see Esensi\Core\Contracts\ExceptionHandlerInterface
 */
trait RedirectingExceptionHandlerTrait
{

    /**
     * @param Throwable $exception
     * @return \Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function handleException(Throwable $exception)
    {
        // Get redirect
        $referer = Request::header('referer');
        $url = empty($referer) ? route('users.signin') : $referer;

        // Add the fragment if defined on the route
        $options = Request::route()->getAction();
        $fragment = array_get($options, 'fragment');
        $url = $fragment ? $url . '#' . $fragment : $url;

        // Send redirect
        return redirect($url)
            ->with('message', $exception->getMessage())
            ->with('code', $exception->getCode())
            ->withErrors($exception->getBag())
            ->withInput();
    }

}
