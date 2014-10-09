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
    $namespace = Config::get('esensi/core::core.namespace', 'esensi/core::');
    $view = Config::get('esensi/core::core.views.public.missing', 'missing');
    return Response::view($namespace . $view, array(), 404);
});

App::error(function(Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception, $code)
{
    Log::error('NotFoundHttpException (' . Request::fullUrl() . ')');

    $namespace = Config::get('esensi/core::core.namespace', 'esensi/core::');
    $view = Config::get('esensi/core::core.views.public.missing', 'missing');
    return Response::view($namespace . $view, array(), 404);
});
