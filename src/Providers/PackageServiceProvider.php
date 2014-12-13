<?php namespace Esensi\Core\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for Esensi component packages
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class PackageServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
    * Add all of the component packages's aliases.
    *
    * @param string $namespace to look for packages
    * @param mixed $packages to get config for
    * @return void
    */
    public function addAliases($namespace, $packages)
    {
        // Make sure we're dealing with an array
        if(is_string($packages))
        {
            $packages = [$packages];
        }

        // Iterate over the packages loading their aliases
        $aliasLoader = AliasLoader::getInstance();
        foreach($packages as $package)
        {
            // Map aliases to classes from the config
            foreach(Config::get($namespace.'::'.$package.'.aliases') as $alias => $class)
            {
                $aliasLoader->alias($alias, $class);
            }
        }
    }

}
