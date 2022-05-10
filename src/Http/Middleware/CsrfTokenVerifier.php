<?php

namespace Esensi\Core\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

/**
 * Verify the CSRF token.
 *
 * @see http://fideloper.com/laravel-http-middleware
 */
class CsrfTokenVerifier extends BaseVerifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Skip CSRF token verification for API calls
        $prefix = config('esensi/core::core.prefixes.api.latest', 'api');
        $isApi = str_contains($request->url(), $prefix);
        if ($isApi) {
            return $next($request);
        }

        // Verify CSRF Token
        return parent::handle($request, $next);
    }

}
