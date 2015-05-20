<?php namespace Esensi\Core\Repositories;

use App\Repositories\Repository as BaseRepository;
use Esensi\Core\Contracts\TrashableRepositoryInterface;
use Esensi\Core\Traits\TrashableRepositoryTrait;

/**
 * Extension of repository that support the trashable interface.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class TrashableRepository extends BaseRepository implements TrashableRepositoryInterface {

    /**
     * Make this repository use trashable models.
     *
     * @see Esensi\Core\Traits\TrashableRepositoryTrait
     */
    use TrashableRepositoryTrait;

}
