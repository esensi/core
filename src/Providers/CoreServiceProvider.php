<?php namespace Esensi\Core\Providers;

use Esensi\Core\Traits\AliasLoaderTrait;
use Esensi\Core\Traits\ConfigLoaderTrait;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for Esensi\Core components package
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class CoreServiceProvider extends ServiceProvider {

    /**
     * Load namespace aliases from the config files.
     *
     * @see Esensi\Core\Traits\AliasLoaderTrait
     */
    use AliasLoaderTrait;

    /**
     * Make use of backported namespaced configs loader.
     *
     * @see Esensi\Core\Traits\ConfigLoaderTrait
     */
    use ConfigLoaderTrait;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $namespace = 'esensi/core';

        // Load configs, views and language files
        $this->loadConfigsFrom(__DIR__ . '/../../config', $namespace);
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', $namespace);
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', $namespace);
        $this->loadAliasesFrom(config_path($namespace), $namespace);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
