<?php namespace Esensi\Core\Models;

use Esensi\Model\Contracts\SoftDeletingModelInterface;
use Esensi\Model\Traits\SoftDeletingModelTrait;

/**
 * Soft Deleting Model
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Models\Model
 * @see Esensi\Model\Contracts\SoftDeletingModelInterface
 */
class SoftModel extends \App\Models\Model implements SoftDeletingModelInterface {

    /**
     * Make model use soft deletes.
     *
     * @see Esensi\Model\Traits\SoftDeletingModelTrait
     */
    use SoftDeletingModelTrait;

}
