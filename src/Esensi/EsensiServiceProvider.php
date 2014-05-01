<?php namespace Esensi;

use \Illuminate\Support\ServiceProvider;

/**
 * Service provider for all Esensi component packages
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class EsensiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of this provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register this service provider.
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
		$this->package('esensi/core', 'esensi', __DIR__.'/..');
	}

	/**
	 * Get the services provided by this provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('esensi');
	}

}