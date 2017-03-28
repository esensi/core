<?php

namespace Esensi\Core\Exceptions;

use App\Exceptions\PermissionVerifierException;
use App\Exceptions\RepositoryException as RepoException;
use App\Repositories\ActivityRepository as Activity;
use Esensi\Core\Contracts\RenderErrorExceptionInterface;
use Esensi\Core\Contracts\RenderRepositoryExceptionInterface;
use Esensi\Core\Traits\RenderErrorExceptionTrait;
use Esensi\Core\Traits\RenderRepositoryExceptionTrait;
use Esensi\User\Contracts\RenderPermissionVerifierExceptionInterface;
use Esensi\User\Traits\RenderPermissionVerifierExceptionTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler implements
   RenderErrorExceptionInterface,
   RenderPermissionVerifierExceptionInterface,
   RenderRepositoryExceptionInterface
{

    /**
     * Render ErrorExceptions as custom views.
     *
     * @see Esensi\Core\Traits\RenderErrorExceptionTrait
     */
    use RenderErrorExceptionTrait;

    /**
     * Render PermissionVerifierException as custom view.
     *
     * @see Esensi\User\Traits\RenderPermissionVerifierExceptionTrait
     */
    use RenderPermissionVerifierExceptionTrait;

    /**
     * Render RepositoryExceptions with the controller class.
     *
     * @see Esensi\Core\Traits\RenderRepositoryExceptionTrait
     */
    use RenderRepositoryExceptionTrait;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        RepoException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        // Log the exception to the Error Log
        $response = parent::report($e);

        // Log the exception to the Activity Log
        try{
            if (class_exists(App\Repositories\ActivityRepository::class)) {
                Activity::addException($e, $e->getCode() ? $e->getCode() : 500);
            }
        }
        catch(Exception $e)
        {
            Log::error($e);
        }

        return $response;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if( $e instanceof ModelNotFoundException )
        {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        // Shortcut for JSON request
        if( $request->ajax() || $request->wantsJson() )
        {
            $data       = $request->all();
            $errors     = method_exists($e, 'getErrors') ? $e->getErrors() : [];
            $message    = $e->getMessage() ?: get_class($e);
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400;
            $code       = $e->getCode() ?: $statusCode;
            $content    = array_filter(compact('errors', 'message', 'code', 'data'));
            return response()->json($content, $statusCode);
        }

        // Render PermissionVerifierException.
        if ( $e instanceof PermissionVerifierException)
        {
            return $this->renderPermissionVerifierException($request, $e);
        }

        // Render RepositoryExceptions according to the controller preference.
        if ($e instanceof RepositoryException)
        {
            return $this->renderRepositoryException($request, $e);
        }

        // Use esensi/core namespaced error views before app namespace. This is
        // not abstracted as a trait because the parent renderHttpException
        // method will still be used and it will if we just let it cascade.
        if ($e instanceof HttpException)
        {
            $status = $e->getStatusCode();
            $line = 'esensi/core::core.views.public.' . $status;
            $view = config($line);
            if( view()->exists($view) )
            {
                $args = [
                    'code'    => $e->getCode() ?: $status,
                    'message' => $e->getMessage(),
                    'status'  => $status,
                ];
                return response()->view($view, $args, $status);
            }
        }

        // Use esensi/core namespaced whoops error view when not in debug mode.
        if ($e instanceof Exception)
        {
            return $this->renderErrorException($request, $e);
        }

        // Render Exception like normal
        return parent::render($request, $e);
    }

}
