<?php

namespace Esensi\{Package}\Providers;

use Esensi\Core\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Route Pattern Service Provider
 *
 * @package   {Vendor}\{Package}
 * @author    {Developer} <{Email}>
 * @copyright {Year} {Company}
 * @license   https://github.com/{vendor}/{package}/blob/master/LICENSE.txt {License} License
 * @link      {URL}
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The namespaces to be applied to all UI controller routes.
     *
     * @var array
     */
    protected $controllers = [
        'admin'  => 'App\Http\Controllers\Admin',
        'public' => 'App\Http\Controllers',
    ];

    /**
     * The namespace to be applied to all API routes.
     *
     * @var string
     */
    protected $apis = 'App\Http\Apis';

    /**
     * Custom patterns for routes used by this package.
     *
     * @var array
     */
    protected $patterns = [];
}
