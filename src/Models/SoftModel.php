<?php

namespace Esensi\Core\Models;

use Esensi\Model\Contracts\SoftDeletingModelInterface;
use Esensi\Model\Traits\SoftDeletingModelTrait;

/**
 * Soft Deleting Model
 *
 * @see Esensi\Core\Models\Model
 * @see Esensi\Model\Contracts\SoftDeletingModelInterface
 */
class SoftModel extends \App\Models\Model implements SoftDeletingModelInterface
{
    /**
     * Make model use soft deletes.
     *
     * @see Esensi\Model\Traits\SoftDeletingModelTrait
     */
    use SoftDeletingModelTrait;

}
