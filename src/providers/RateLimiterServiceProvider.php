<?php namespace Esensi\Core\Providers;

use \Esensi\Core\Providers\PackageServiceProvider;
use \Esensi\Core\Middlewares\RateLimiter;

/**
 * Service provider for the rate limiting middleware
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class RateLimiterServiceProvider extends PackageServiceProvider {

    /**
     * Registers the resource dependencies
     *
     * @return void
     */
    public function register()
    {
        $this->app->middleware( new RateLimiter($this->app) );
    }
}