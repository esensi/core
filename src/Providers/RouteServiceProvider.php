<?php

namespace Esensi\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use \ReflectionClass;

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
    public function boot()
    {
        $this->bindPatterns();

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map()
    {
        $this->mapControllers();
        $this->mapApis();
    }

    /**
     * Bind any patterns to avoid package collisions.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    protected function bindPatterns()
    {
        foreach ($this->patterns as $name => $pattern) {
            Route::pattern($name, $pattern);
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
    protected function mapControllers()
    {
        $path = $this->routesPath();
        foreach ($this->controllers as $ui => $namespace) {
            $this->groupRoutesByNamespace($namespace, $ui);
        }
    }

    /**
     * Map all routes for APIs under the API namespace.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    protected function mapApis()
    {
        if( $this->apis !== null ) {
            $this->groupRoutesByNamespace($this->apis, 'api');
        }
    }

    /**
     * Group all the routes in a file by a controller namespace.
     *
     * @param \Illuminate\Routing\Router $router
     * @param string $namespace
     * @param string $file name
     * @return void
     */
    protected function groupRoutesByNamespace($namespace, $file)
    {
        $path = $this->routesPath();
        Route::group(['namespace' => $namespace], function ($router) use ($path, $file) {
            require $path . $file . '.php';
        });
    }

    /**
     * Get the path to the routes based on the called class file path.
     *
     * @return string
     */
    protected function routesPath()
    {
        $reflector = new ReflectionClass(get_class($this));
        return dirname($reflector->getFileName()) . '/../Http/Routes/';
    }
}
