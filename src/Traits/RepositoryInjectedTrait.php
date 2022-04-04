<?php

namespace Esensi\Core\Traits;

use App\Repositories\Repository;

/**
 * Trait implementation of repository injection interface.
 *
 * @see Esensi\Core\Contracts\RepositoryInjectedInterface
 */
trait RepositoryInjectedTrait
{
    /**
     * Injected repositories.
     *
     * @var array of \Esensi\Core\Repository\Repository
     */
    protected $repositories = [];

    /**
     * Get the specified repository by name.
     *
     * @param  string  $name (optional) of repository
     * @return Esensi\Core\Repository\Repository
     */
    public function getRepository($name = null)
    {
        $name = is_null($name) ? $this->package : $name;
        return $this->repositories[$name];
    }

    /**
     * Set the specified repository by name.
     *
     * @param  \Esensi\Core\Repository\Repository  $repository
     * @param  string  $name (optional) of repository
     * @return void
     */
    public function setRepository(Repository $repository, $name = null)
    {
        $name = is_null($name) ? $this->package : $name;
        $this->repositories[$name] = $repository;
    }

}
