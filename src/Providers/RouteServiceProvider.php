<?php namespace Esensi\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Route Pattern Service Provider
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->pattern('id', '[0-9]+');
        $router->pattern('ids', '[0-9\+]+');
        $router->pattern('slug', '[a-zA-Z][a-zA-Z0-9\-\_]+');
        $router->pattern('action', '[a-zA-Z][a-zA-Z0-9\-\_]+');
        $router->pattern('token', '[a-zA-Z0-9]+');
        $router->pattern('relationship', '[a-zA-Z0-9\_\-\+]+');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        //
    }

}
