<?php

namespace Esensi\Core\Http\Middleware;

use App\Repositories\ActivityRepository as Activity;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;

/**
 * User Filter to Allow Only Authenticated Users
 *
 */
class AuthenticationVerifier
{
    /**
     * The Guard implementation.
     *
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Illuminate\Contracts\Auth\Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            // Log the action to the Activity Log
            $message = Lang::get('esensi/activity::activity.messages.unauthorized');
            Activity::addAction($message, [
                'code' => 401,
                'label' => 'esensi.core.unauthorized'
            ]);

            // Block the request for AJAX
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

            // Redirect to a safe route
            $options = $request->route()->getAction();
            $fragment = array_get($options, 'fragment');
            $url = route('users.login');
            $url = $fragment ? $url . '#' . $fragment : $url;
            return redirect()->guest($url);
        }

        return $next($request);
    }

}
