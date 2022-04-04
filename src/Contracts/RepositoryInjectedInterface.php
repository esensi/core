<?php

namespace Esensi\Core\Contracts;

use Esensi\Core\Repositories\Repository;

/**
 * Interface for injecting repositories into a class
 *
 */
interface RepositoryInjectedInterface
{
    /**
     * Get the specified repository by name
     *
     * @param  string  $name (optional) of repository
     * @return Esensi\Core\Repository\Repository
     */
    public function getRepository($name = null);

    /**
     * Set the specified repository by name
     *
     * @param  \Esensi\Core\Repository\Repository  $repository
     * @param  string  $name (optional) of repository
     * @return void
     */
    public function setRepository(Repository $repository, $name = null);

}
