<?php namespace Alba\User;

use Alba\Core\Providers\ModuleServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/**
 * Service provider for Alba\User module
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class UserServiceProvider extends ModuleServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->addAliases(['user', 'role', 'permission', 'token']);

        require __DIR__.'/filters.php';
        require __DIR__.'/routes.php';
    }
}