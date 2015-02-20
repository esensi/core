<?php namespace Esensi\Core\Providers;

use Esensi\Loaders\Providers\ServiceProvider;

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
     * The namespace of the loaded config files.
     *
     * @var string
     */
    protected $namespace = 'esensi/core';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $namespace = $this->getNamespace();

        // Load configs, views and language files
        $this->loadConfigsFrom(__DIR__ . '/../../config', $namespace, $this->publish);
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

    }

}
