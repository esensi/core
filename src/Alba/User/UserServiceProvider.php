<?php namespace Alba\User;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for Alba\User module
 *
 * @author diego <diego@emersonmedia.com>, daniel <daniel@bexarcreative.com>
 */
class UserServiceProvider extends ServiceProvider {

    /**
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
    * Registers the resource dependencies
    *
    * @return void
    */
    public function register()
    {

    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__.'/filters.php';
        require __DIR__.'/routes.php';
    }
}