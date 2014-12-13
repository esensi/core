<?php namespace Esensi\Core\Providers;

use Esensi\Core\Middlewares\RateLimiter;
use Esensi\Core\Providers\PackageServiceProvider;

/**
 * Service provider for the rate limiting middleware
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class RateLimiterServiceProvider extends PackageServiceProvider {

    /**
     * Registers the resource dependencies.
     *
     * @return void
     */
    public function register()
    {
        $this->app->middleware( new RateLimiter($this->app) );
    }

}
