<?php namespace Esensi\Core\Repositories;

use \Esensi\Core\Contracts\CruddableRepositoryInterface;
use \Esensi\Core\Contracts\ExceptionalRepositoryInterface;
use \Esensi\Core\Contracts\FilterableRepositoryInterface;
use \Esensi\Core\Contracts\ModelInjectedInterface;
use \Esensi\Core\Contracts\PackagedInterface;
use \Esensi\Core\Contracts\ResourcefulRepositoryInterface;
use \Esensi\Core\Contracts\TrashableRepositoryInterface;
use \Esensi\Core\Traits\CruddableRepositoryTrait;
use \Esensi\Core\Traits\ExceptionalRepositoryTrait;
use \Esensi\Core\Traits\FilterableRepositoryTrait;
use \Esensi\Core\Traits\ModelInjectedTrait;
use \Esensi\Core\Traits\PackagedTrait;
use \Esensi\Core\Traits\ResourcefulRepositoryTrait;
use \Esensi\Core\Traits\TrashableRepositoryTrait;

use \Illuminate\Database\Eloquent\Model;

/**
 * Complete implementation of repository interfaces
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class Repository implements CruddableRepositoryInterface,
    ExceptionalRepositoryInterface,
    FilterableRepositoryInterface,
    ModelInjectedInterface,
    PackagedInterface,
    ResourcefulRepositoryInterface,
    TrashableRepositoryInterface {

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
     * Make this repository use active record models
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
     * Make this repository a resource adapter
     *
     * @see \Esensi\Core\Traits\ResourcefulRepositoryTrait
     */
    use ResourcefulRepositoryTrait;

    /**
     * Make this repository use trashable models
     *
     * @see \Esensi\Core\Traits\TrashableRepositoryTrait
     */
    use TrashableRepositoryTrait;

    /**
     * Inject dependencies
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Esensi\Core\Repositories\Repository
     */
    public function __construct( Model $model )
    {
        $this->setModel( $model, 'default' );
    }

}