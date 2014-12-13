<?php namespace Esensi\Core\Providers;

use Esensi\Core\Providers\PackageServiceProvider;
use League\StackRobots\Robots;

/**
 * Service provider for the robots middleware
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class RobotsServiceProvider extends PackageServiceProvider {

    /**
     * Registers the resource dependencies.
     *
     * @return void
     */
    public function register()
    {
        $stack = new Robots($this->app);
        $this->app->middleware( $stack );
    }

}
