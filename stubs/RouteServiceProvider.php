<?php

namespace Esensi\{Package}\Providers;

use Illuminate\Routing\Router;
use Esensi\Core\Providers\RouteServiceProvider as CoreRouteServiceProvider;

/**
 * Route Pattern Service Provider
 *
 * @package   {Vendor}\{Package}
 * @author    {Developer} <{Email}>
 * @copyright {Year} {Company}
 * @license   https://github.com/{vendor}/{package}/blob/master/LICENSE.txt {License} License
 * @link      {URL}
 */
class RouteServiceProvider extends CoreRouteServiceProvider
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
}
