<?php namespace Esensi\Core\Http\Middleware;

use App\Models\Activity;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;

/**
 * User Filter to Allow Only Authenticated Users
 *
 * @package Esensi\Esensi
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/esensi/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class AuthenticationVerifier {

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
        if ($this->auth->guest())
        {
            // Log the action to the Activity Log
            $message = Lang::get('esensi/activity::activity.messages.unauthorized');
            Activity::addAction($message, [
                'code'  => 401,
                'label' => 'esensi.core.unauthorized'
            ]);

            // Block the request for AJAX
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }

            // Redirect to a safe route
            return redirect()->guest(route('users.login'));
        }

        return $next($request);
    }

}
