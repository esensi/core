<?php

namespace {Vendor}\{Package}\Providers;

use Esensi\Loaders\Providers\ServiceProvider;

/**
 * Service provider for {Vendor}\{Package} components package.
 *
 * @package {Vendor}\{Package}
 * @author {Developer} <{Email}>
 * @copyright {Year} {Company}
 * @license https://github.com/{vendor}/{package}/blob/master/LICENSE.txt {License} License
 * @link {URL}
 */
class {Package}ServiceProvider extends ServiceProvider
{
    /**
     * The namespace of the loaded config files.
     *
     * @var string
     */
    protected $namespace = '{vendor}/{package}';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $namespace = $this->namespace;

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
