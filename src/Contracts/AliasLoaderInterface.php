<?php namespace Esensi\Core\Contracts;

/**
 * Alias Loader Interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface AliasLoaderInterface {

    /**
     * Load the alias found in configs from a path under a namespace.
     *
     * @param string $path
     * @param string $namespace
     * @return void
     */
    public function loadAliasesFrom($path, $namespace);

}
