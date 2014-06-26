<?php namespace Esensi\Build;

use \Esensi\Core\Providers\PackageServiceProvider;
use \Illuminate\Support\Facades\Config;
use \Illuminate\Support\Facades\Event;

/**
 * Service provider for Esensi\Build components package
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class BuildServiceProvider extends PackageServiceProvider {

    /**
     * Registers the resource dependencies
     *
     * @return void
     */
    public function register()
    {
        require __DIR__ . '/helpers.php';

        Event::listen('artisan.start', function(\Illuminate\Console\Application $artisan)
        {
            foreach(Config::get('esensi/core::build.aliases', []) as $alias => $command)
            {
                $artisan->add(new $command());
            }
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Bind build class aliases
        $this->addAliases('esensi/core', ['build']);

        // Get Blade compiler
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        // Add @scripts($dependency1, $dependency2, $dependencyN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('scripts');

            return preg_replace($matcher, '$1<?php echo build_scripts$2; ?>', $value);
        });

        // Add @styles($dependency1, $dependency2, $dependencyN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('styles');

            return preg_replace($matcher, '$1<?php echo build_styles$2; ?>', $value);
        });
    }
}
