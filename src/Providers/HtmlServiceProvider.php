<?php

namespace Esensi\Core\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for custom HTML extensions.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Add HTML extensions
        $extensions = config('esensi/core::html.extensions', []);
        foreach( $extensions as $extension => $class)
        {
            $method = lcfirst(studly_case($extension));
            $callable = [$class, $method];
            $this->app['html']->macro($extension, function() use ($callable)
            {
                $parameters = func_get_args();
                return call_user_func_array($callable, $parameters);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
