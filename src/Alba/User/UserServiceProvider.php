<?php namespace Alba\User;

use Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Registers the resource dependencies
	 *
	 * @return voide
	 */
	public function register(){
		
		$this->app->singleton('Alba\User\Contracts\UserResourceInterface', 'Alba\User\Resources\UserResource');
		
		$aliasLoader = AliasLoader::getInstance();
		$aliasLoader->alias('Permission', 'Alba\User\Models\Permission');
		$aliasLoader->alias('Role', 'Alba\User\Models\Role');
		$aliasLoader->alias('User', 'Alba\User\Models\User');
		$aliasLoader->alias('UserAdmin', 'Alba\User\Controllers\UserAdmin');
		$aliasLoader->alias('UserController', 'Alba\User\Controllers\UserController');
		$aliasLoader->alias('UserApi', 'Alba\User\Resources\UserResource');
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