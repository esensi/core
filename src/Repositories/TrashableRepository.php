<?php namespace Esensi\Core\Repositories;

use Esensi\Core\Contracts\TrashableRepositoryInterface;
use Esensi\Core\Repositories\Repository;
use Esensi\Core\Traits\TrashableRepositoryTrait;

/**
 * Extension of repository that support the trashable interface.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class TrashableRepository extends Repository implements TrashableRepositoryInterface {

    /**
     * Make this repository use trashable models.
     *
     * @see Esensi\Core\Traits\TrashableRepositoryTrait
     */
    use TrashableRepositoryTrait;

}
