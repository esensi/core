<?php

namespace Esensi\Core\Http\Middleware;

use App\Http\Middleware\AuthenticationVerifier;
use App\Repositories\ActivityRepository as Activity;
use Closure;
use Illuminate\Support\Facades\Lang;

/**
 * User Filter to Allow RESTful Authentication
 *
 */
class ApiAuthenticationVerifier extends AuthenticationVerifier
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            $response = $this->auth->onceBasic();
            if ($response) {
                // Log the action to the Activity Log
                $message = Lang::get('esensi/activity::activity.messages.unauthorized');
                Activity::addAction($message, [
                    'code' => 401,
                    'label' => 'esensi.core.unauthorized'
                ]);

                // Block the request for AJAX
                return $response;
            }
        }

        return $next($request);
    }

}
