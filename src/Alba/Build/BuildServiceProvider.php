<?php namespace Alba\Build;

use Alba\Core\Providers\ModuleServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

/**
 * Service provider for Alba\Build module
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class BuildServiceProvider extends ModuleServiceProvider {

    /**
     * Registers the resource dependencies
     *
     * @return void
     */
    public function register()
    {
    	require __DIR__.'/helpers.php';

        Event::listen('artisan.start', function(\Illuminate\Console\Application $artisan)
		{
			foreach(Config::get('alba::build.aliases', []) as $alias => $command)
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
        $this->addAliases(['build']);

        // Get Blade compiler
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        // Add @scripts($collection1, $collection2, $collectionN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('scripts');
            
            return preg_replace($matcher, '$1<?php echo build_scripts$2; ?>', $value);
        });

        // Add @styles($collection1, $collection2, $collectionN)
        $blade->extend(function($value, $compiler)
        {
            $matcher = $compiler->createMatcher('styles');
            
            return preg_replace($matcher, '$1<?php echo build_styles$2; ?>', $value);
        });
    }
}