<?php namespace Esensi\Core\Providers;

use \Esensi\Core\Providers\PackageServiceProvider;
use \League\StackRobots\Robots;

/**
 * Service provider for the robots middleware
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class RobotsServiceProvider extends PackageServiceProvider {

    /**
     * Registers the resource dependencies
     *
     * @return void
     */
    public function register()
    {
        $stack = new Robots($this->app);
        $this->app->middleware( $stack );
    }
}
