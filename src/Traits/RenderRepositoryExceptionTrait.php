<?php

namespace Esensi\Core\Traits;

use App\Exceptions\RepositoryException;

/**
 * Trait that renders RepositoryExceptions
 *
 * @see Esensi\Core\Contracts\RenderRepositoryExceptionInterface
 */
trait RenderRepositoryExceptionTrait
{
    /**
     * Render a Repository Exception into an HTTP respons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exceptions\RepositoryException  $e
     * @return \Illuminate\Http\Response
     */
    public function renderRepositoryException($request, RepositoryException $e)
    {
        // Get the controller that handled the request
        $options = $request->route()->getAction();
        $action = array_get($options, 'uses');
        if (! empty($action)) {
            // Render the exception according to the controller preference
            $action = explode('@', $action);
            $class = app(head($action));
            if (method_exists($class, 'handleException')) {
                return $class->handleException($e);
            }
        }

        // If this was a console command then no route/controller was used
        // so handle this exception like normal
        return parent::render($request, $e);
    }

}
