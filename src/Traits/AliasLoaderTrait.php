<?php namespace Esensi\Core\Traits;

use Illuminate\Foundation\AliasLoader;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

/**
 * Trait implementation of alias loader interface.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\AliasLoaderInterface
 */
trait AliasLoaderTrait {

    /**
     * Load the alias found in configs from a path under a namespace.
     *
     * @param string $path
     * @param string $namespace
     * @return void
     */
    public function loadAliasesFrom($path, $namespace)
    {
        $directory = config_path($namespace);

        // Get the alias loader
        $loader = AliasLoader::getInstance();

        // Wrapped in a try catch because Finder squawks when there is no directory
        try{

            // Load the namespaced config files
            $files = Finder::create()->files()->name('*.php')->in($directory);
            foreach($files as $file)
            {
                $key = $namespace . '::' . basename($file->getRealPath(), '.php') . '.aliases';
                foreach( $this->app['config']->get($key, []) as $alias => $class)
                {
                    $loader->alias($alias, $class);
                }
            }

        } catch( InvalidArgumentException $e){}
    }

}
