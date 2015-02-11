<?php namespace Esensi\Core\Traits;

use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

/**
 * Trait implementation of config loader interface.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\ConfigLoaderInterface
 */
trait ConfigLoaderTrait {

    /**
     * Load the configs from a path under a namespace.
     * Also makes them available for publishing.
     *
     * @param string $path
     * @param string $namespace
     * @return void
     */
    public function loadConfigsFrom($path, $namespace)
    {
        $directory = config_path($namespace);

        // Get the configs that need to be published
        $configs = [];
        $files = Finder::create()->files()->name('*.php')->in($path);
        foreach($files as $file)
        {
            $configs[$file->getRealPath()] = $directory . '/' . basename($file->getRealPath());
        }

        // Publish the configs to the app namespace
        $this->publishes($configs, 'config');

        // Wrapped in a try catch because Finder squawks when there is no directory
        try{

            // Load the namespaced config files
            $files = Finder::create()->files()->name('*.php')->in($directory);
            foreach($files as $file)
            {
                $key = $namespace . '::' . basename($file->getRealPath(), '.php');
                $this->app['config']->set($key, require $file->getRealPath());
            }

        } catch( InvalidArgumentException $e){}
    }

}
