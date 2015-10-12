<?php

namespace Esensi\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Route Pattern Service Provider
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The namespaces to be applied to all UI controller routes.
     *
     * @var array
     */
    protected $controllers = [];

    /**
     * The namespace to be applied to all API routes.
     *
     * @var string
     */
    protected $apis;

    /**
     * Custom patterns for routes used by this package.
     *
     * @var array
     */
    protected $patterns = [
        'id'           => '[0-9]+',
        'ids'          => '[0-9\+]+',
        'slug'         => '[a-zA-Z][a-zA-Z0-9\-\_]+',
        'action'       => '[a-zA-Z][a-zA-Z0-9\-\_]+',
        'token'        => '[a-zA-Z0-9]+',
        'relationship' => '[a-zA-Z0-9\_\-\+]+',
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $this->bindPatterns($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapControllers($router);
        $this->mapApis($router);
    }

    /**
     * Bind any patterns to avoid package collisions.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    protected function bindPatterns(Router $router)
    {
        foreach ($this->patterns as $name => $pattern) {
            $router->pattern($name, $pattern);
        }
    }

    /**
     * Map all routes for UI controllers under the UI controller namespace.
     *
     * Each entry expects a routes file with a name matching the key.
     *
     * @example $this->controllers['foo'] expects Http/Routes/foo.php.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    protected function mapControllers(Router $router)
    {
        foreach ($this->controllers as $ui => $namespace) {
            $router->group(['namespace' => $namespace], function ($router) use ($ui) {
                require_once __DIR__ . '/../Http/Routes/' . $ui . '.php';
            });
        }
    }

    /**
     * Map all routes for APIs under the API namespace.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    protected function mapApis(Router $router)
    {
        if( $this->apis !== null ) {
            $router->group(['namespace' => $this->apis], function ($router) {
                require_once __DIR__ . '/../Http/Routes/api.php';
            });
        }
    }
}
