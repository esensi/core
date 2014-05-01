<?php namespace Esensi\Core\Providers;

use \Illuminate\Foundation\AliasLoader;
use \Illuminate\Support\Facades\Artisan;
use \Illuminate\Support\Facades\Config;
use \Illuminate\Support\ServiceProvider;

/**
 * Service provider for Esensi component packages
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class ModuleServiceProvider extends ServiceProvider {

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
        
    }

    /**
    * Add all of the component packages's aliases
    *
    * @param mixed $packages to get config for
    * @return void
    */
    public function addAliases($packages)
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
            foreach(Config::get('esensi::'.$package.'.aliases') as $alias => $class)
            {
                $aliasLoader->alias($alias, $class);
            }
        }
    }

}