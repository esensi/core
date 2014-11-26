<?php namespace Esensi\Core\Repositories;

use \Esensi\Core\Contracts\CruddableRepositoryInterface;
use \Esensi\Core\Contracts\ExceptionalRepositoryInterface;
use \Esensi\Core\Contracts\FindableRepositoryInterface;
use \Esensi\Core\Contracts\FilterableRepositoryInterface;
use \Esensi\Core\Contracts\ModelInjectedInterface;
use \Esensi\Core\Contracts\PackagedInterface;
use \Esensi\Core\Contracts\RepositoryInjectedInterface;
use \Esensi\Core\Contracts\ResourcefulRepositoryInterface;
use \Esensi\Core\Traits\CruddableRepositoryTrait;
use \Esensi\Core\Traits\ExceptionalRepositoryTrait;
use \Esensi\Core\Traits\FindableRepositoryTrait;
use \Esensi\Core\Traits\FilterableRepositoryTrait;
use \Esensi\Core\Traits\ModelInjectedTrait;
use \Esensi\Core\Traits\PackagedTrait;
use \Esensi\Core\Traits\RepositoryInjectedTrait;
use \Esensi\Core\Traits\ResourcefulRepositoryTrait;
use \Esensi\Core\Models\Model;

/**
 * Complete implementation of repository interfaces
 *
 * @author daniel <daniel@bexarcreative.com>
 */
abstract class Repository implements
    CruddableRepositoryInterface,
    ExceptionalRepositoryInterface,
    FindableRepositoryInterface,
    FilterableRepositoryInterface,
    ModelInjectedInterface,
    PackagedInterface,
    ResourcefulRepositoryInterface {

    /**
     * Make this repository use a CRUD interface
     *
     * @see \Esensi\Core\Traits\CruddableRepositoryTrait
     */
    use CruddableRepositoryTrait;

    /**
     * Make this repository throw exceptions
     *
     * @see \Esensi\Core\Traits\ExceptionalRepositoryTrait
     */
    use ExceptionalRepositoryTrait;

    /**
     * Make this repository use filter methods
     *
     * @see \Esensi\Core\Traits\FilterableRepositoryTrait
     */
    use FilterableRepositoryTrait;

    /**
     * Make this repository use find aliases
     *
     * @see \Esensi\Core\Traits\FindableRepositoryTrait
     */
    use FindableRepositoryTrait;

    /**
     * Make this repository use injected models
     *
     * @see \Esensi\Core\Traits\ModelInjectedTrait
     */
    use ModelInjectedTrait;

    /**
     * Package this repository
     *
     * @see \Esensi\Core\Traits\PackagedTrait
     */
    use PackagedTrait;

    /**
     * Make this repository use injected repositories
     *
     * @see \Esensi\Core\Traits\RepositoryInjectedTrait
     */
    use RepositoryInjectedTrait;

    /**
     * Make this repository a resource adapter
     *
     * @see \Esensi\Core\Traits\ResourcefulRepositoryTrait
     */
    use ResourcefulRepositoryTrait;

    /**
     * Inject dependencies
     *
     * @param \Esensi\Core\Models\Model $model
     * @return \Esensi\Core\Repositories\Repository
     */
    public function __construct( Model $model )
    {
        $this->setModel( $model );
    }

}
