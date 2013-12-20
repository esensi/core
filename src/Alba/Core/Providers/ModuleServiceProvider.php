<?php namespace Alba\Core\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for Alba modules
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
    * Add all of the module's aliases
    *
    * @param string $module to get config for
    * @return void
    */
    public function addAliases($module)
    {
        // Map aliases to classes from the config
        $aliasLoader = AliasLoader::getInstance();
        foreach(Config::get('alba::'.$module.'.aliases') as $alias => $class)
        {
            $aliasLoader->alias($alias, $class);
        }
    }

}