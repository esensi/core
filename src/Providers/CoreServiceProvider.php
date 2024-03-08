<?php

namespace Esensi\Core\Providers;

use Esensi\Loaders\Providers\ServiceProvider;
use Illuminate\Support\Facades\Request;

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
        $this->loadMacros();
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

    /**
     * @return void
     */
    private function loadMacros(): void
    {
        Request::macro('onlyWithNulls', function ($keys) {
            $keys = is_array($keys) ? $keys : func_get_args();
            $all = Request::all();
            $result = [];
            foreach ((array) $keys as $key) {
                $result[$key] = $all[$key] ?? null;
            }
            return $result;
        });
    }

}
