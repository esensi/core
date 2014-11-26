<?php namespace Esensi\Core\Repositories;

use \Esensi\Core\Contracts\TrashableRepositoryInterface;
use \Esensi\Core\Traits\TrashableRepositoryTrait;
use \Esensi\Core\Repositories\Repository;

/**
 * Extension of repository that support the trashable interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
abstract class TrashableRepository extends Repository implements
    TrashableRepositoryInterface {

    /**
     * Make this repository use trashable models
     *
     * @see \Esensi\Core\Traits\TrashableRepositoryTrait
     */
    use TrashableRepositoryTrait;

}
