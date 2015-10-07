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
     * The namespaces is applied to all UI controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var array
     */
    protected $controllers = [
        'admin'  => 'App\Http\Controllers\Admin',
        'public' => 'App\Http\Controllers\Public',
    ];

    /**
     * This namespace is applied to all API routes.
     *
     * @var string
     */
    protected $apis = 'App\Http\Apis';

    /**
     * Any custom patterns the package should set to avoid collisions.
     *
     * @var array
     */
    protected $patterns = [];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
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
     * @param  \Illuminate\Routing\Router  $router
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
     */
    private function bindPatterns(Router $router)
    {
        $router->pattern('id', '[0-9]+');
        $router->pattern('ids', '[0-9\+]+');
        $router->pattern('slug', '[a-zA-Z][a-zA-Z0-9\-\_]+');
        $router->pattern('action', '[a-zA-Z][a-zA-Z0-9\-\_]+');
        $router->pattern('token', '[a-zA-Z0-9]+');
        $router->pattern('relationship', '[a-zA-Z0-9\_\-\+]+');

        foreach ($this->patterns as $name => $pattern) {
            $router->pattern($name, $pattern);
        }
    }

    /**
     * This namespace is applied to the controller routes in your routes file.
     * Each entry expects a routes file with a name matching the key.
     * ie: $this->controllers['admin'] expects Routes/admin.php.
     *
     * @param \Illuminate\Routing\Router $router
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
     * Define the routes for the API.
     *
     * @param \Illuminate\Routing\Router $router
     */
    protected function mapApis(Router $router)
    {
        $router->group(['namespace' => $this->apis], function ($router) {
            require_once __DIR__ . '/../Http/Routes/api.php';
        });
    }
}
