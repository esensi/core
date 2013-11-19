<?php

namespace Alba\User;

use Illuminate\Support\ServiceProvider;

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
    public function register() {
        $this->app->singleton('Alba\User\Repositories\Contracts\UserRepositoryInterface', 'Alba\User\Repositories\DbUserRepository');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__.'/routes.php';
    }

}