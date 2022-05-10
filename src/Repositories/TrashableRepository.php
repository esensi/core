<?php

namespace Esensi\Core\Repositories;

use App\Repositories\Repository as BaseRepository;
use Esensi\Core\Contracts\TrashableRepositoryInterface;
use Esensi\Core\Traits\TrashableRepositoryTrait;

/**
 * Extension of repository that support the trashable interface.
 *
 */
class TrashableRepository extends BaseRepository implements TrashableRepositoryInterface
{
    /**
     * Make this repository use trashable models.
     *
     * @see Esensi\Core\Traits\TrashableRepositoryTrait
     */
    use TrashableRepositoryTrait;

}
