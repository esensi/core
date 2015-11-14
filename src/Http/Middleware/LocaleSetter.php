<?php

namespace Esensi\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class LocaleSetter
{
    /**
     * Set the locale for the language translations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Use default locale
        $locale = app()->getLocale();

        // Use locale from header
        if( ! is_null(Request::header('Accept-Language', null)) )
        {
            $language = Request::getPreferredLanguage();
            $locale = head(explode('_', $language));
        }

        // Use locale from user preferences
        if( Session::has('locale') )
        {
            $locale = Session::get('locale');
        }

        // Use locale from query parameter
        if( Input::has('locale') )
        {
            $locale = Input::get('locale');
        }

        // Save the locale to the session
        Session::set('locale', $locale);

        // Set the locale for the remaining requests
        App::setLocale($locale);

        // Continue processing middlewares
        return $next($request);
    }
}
