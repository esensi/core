<?php

namespace Esensi\Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Guest Filter to Allow Only Guest Users
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/esensi/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class AuthenticatedRedirector
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
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
        if ($this->auth->check())
        {
            return redirect(route('index'));
        }

        return $next($request);
    }

}
