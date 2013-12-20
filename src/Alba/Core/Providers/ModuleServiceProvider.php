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
    * @param mixed $module to get config for
    * @return void
    */
    public function addAliases($modules)
    {
        // Make sure we're dealing with an array
        if(is_string($modules))
        {
            $modules = [$modules];
        }

        // Iterate over the modules loading their aliases
        $aliasLoader = AliasLoader::getInstance();
        foreach($modules as $module)
        {
            // Map aliases to classes from the config
            foreach(Config::get('alba::'.$module.'.aliases') as $alias => $class)
            {
                $aliasLoader->alias($alias, $class);
            }
        }
    }

}