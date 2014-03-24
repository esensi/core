<?php

/*
|--------------------------------------------------------------------------
| Error Filters
|--------------------------------------------------------------------------
|
| When calling an API route, all error exceptions should be handled in such
| a way that they return JSON. These filters should be bound to the route's
| before filter to help with the formatting. It is responsible for binding
| all of the custom exceptions to more appropriate error handlers.
|
*/

App::missing(function($exception)
{
    $view = Config::get('alba::core.views.missing', 'layouts.missing');
    return Response::view($view, array(), 404);
});

Route::filter('error.default', function($route, $request, $fragment = null)
{
    App::error(function(Exception $exception, $code, $fromConsole)
    {
        Log::error($exception);
    });

    App::error(function(Alba\Core\Exceptions\ResourceException $exception, $code, $fromConsole) use ($fragment)
    {
        Log::info($exception);
        return $exception->handleWithRedirect($fragment);
    });
});

Route::filter('error.api', function()
{

    App::error(function(Exception $exception, $code, $fromConsole)
    {
        Log::error($exception);
    });

    App::error(function(Alba\Core\Exceptions\ResourceException $exception, $code, $fromConsole)
    {
        Log::info($exception);
        return $exception->handleForApi();
    });
});