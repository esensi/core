<?php namespace Esensi\Core\Repositories;

use \Esensi\Core\Contracts\FilterableRepositoryInterface;
use \Esensi\Core\Contracts\TrashableRepositoryInterface;
use \Esensi\Core\Contracts\ResourcefulRepositoryInterface;
use \Esensi\Core\Contracts\CruddableRepositoryInterface;
use \Esensi\Core\Contracts\ModeledRepositoryInterface;
use \Esensi\Core\Traits\FilterableRepositoryTrait;
use \Esensi\Core\Traits\TrashableRepositoryTrait;
use \Esensi\Core\Traits\ResourcefulRepositoryTrait;
use \Esensi\Core\Traits\CruddableRepositoryTrait;
use \Esensi\Core\Traits\ModeledRepositoryTrait;

/**
 * Complete implementation of repository interfaces
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\FilterableRepositoryInterface
 * @see \Esensi\Core\Contracts\TrashableRepositoryInterface
 * @see \Esensi\Core\Contracts\ResourcefulRepositoryInterface
 * @see \Esensi\Core\Contracts\CruddableRepositoryInterface
 * @see \Esensi\Core\Contracts\ModeledRepositoryInterface
 */
class Repository implements FilterableRepositoryInterface,
    TrashableRepositoryInterface, ResourcefulRepositoryInterface,
    CruddableRepositoryInterface, ModeledRepositoryInterface {
    
    /**
     * Make this repository a resourceful adapter
     *
     * @see \Esensi\Core\Traits\ResourcefulRepositoryTrait
     * @see \Esensi\Core\Traits\FilterableRepositoryTrait
     * @see \Esensi\Core\Traits\TrashableRepositoryTrait
     * @see \Esensi\Core\Traits\CruddableRepositoryTrait
     * @see \Esensi\Core\Traits\ModeledRepositoryTrait
     */
    use ResourcefulRepositoryTrait;
    
}