<?php

namespace Esensi\Core\Providers;

use Esensi\Loaders\Providers\ServiceProvider;

/**
 * Service provider for Esensi\Core components package
 *
 */
class CoreServiceProvider extends ServiceProvider
{
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
        // Load configs, views and language files
        $this->loadConfigsFrom(__DIR__ . '/../../config', $this->namespace, $this->publish);
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', $this->namespace);
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', $this->namespace);
        $this->loadAliasesFrom(config_path($this->namespace), $this->namespace);
    }

    /**
     * Register any application services.
     * This is provided here so we don't have to redeclare
     * and empty one on a parent class if it is not needed.
     *
     * @return void
     */
    public function register()
    {

    }

}
