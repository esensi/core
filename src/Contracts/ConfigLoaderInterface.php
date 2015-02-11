<?php namespace Esensi\Core\Contracts;

/**
 * Config Loader Interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface ConfigLoaderInterface {

    /**
     * Load the configs from a path under a namespace.
     * Also makes them available for publishing.
     *
     * @param string $path
     * @param string $namespace
     * @return void
     */
    public function loadConfigsFrom($path, $namespace);

}
