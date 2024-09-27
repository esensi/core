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
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * @param  \Throwable  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        // Log the exception to the Error Log
        $response = parent::report($e);

        // Log the exception to the Activity Log
        try {
            if (class_exists(App\Repositories\ActivityRepository::class)) {
                Activity::addException($e, $e->getCode() ? $e->getCode() : 500);
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        return $response;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        // Shortcut for JSON request
        if ($request->ajax() || $request->wantsJson()) {
            $data = $request->all();
            $errors = method_exists($e, 'getErrors') ? $e->getErrors() : [];
            $message = $e->getMessage() ?: get_class($e);
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400;
            $code = $e->getCode() ?: $statusCode;
            $content = array_filter(compact('errors', 'message', 'code', 'data'));
            return response()->json($content, $statusCode);
        }

        // Render PermissionVerifierException.
        if ($e instanceof PermissionVerifierException) {
            return $this->renderPermissionVerifierException($request, $e);
        }

        // Render RepositoryExceptions according to the controller preference.
        if ($e instanceof RepositoryException) {
            return $this->renderRepositoryException($request, $e);
        }

        $statusCode = match (true) {
            $e instanceof HttpException => $e->getStatusCode(),
            $e instanceof AuthorizationException => $e->status(),
            default => null,
        };

        if (!is_null($statusCode)) {
            $view = config("esensi/core::core.views.public.{$statusCode}");
            if (view()->exists($view)) {
                return response()->view(
                    $view,
                    [
                        'code' => $e->getCode() ?: $statusCode,
                        'message' => $e->getMessage(),
                        'status' => $statusCode,
                    ],
                    $statusCode
                );
            }
        }

        return $this->renderErrorException($request, $e);
    }
}
