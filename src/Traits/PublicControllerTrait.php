<?php namespace Esensi\Core\Traits;

use Esensi\Core\Traits\ApiAncestryControllerTrait;
use Esensi\Core\Traits\RedirectingExceptionHandlerTrait;

/**
 * Trait that encapsulates other public controller related traits.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
trait PublicControllerTrait {

    /**
     * Allow access to the API ancestor.
     *
     * @see Esensi\Core\Traits\ApiAncestryControllerTrait
     */
    use ApiAncestryControllerTrait;

    /**
     * Make exceptions return a redirect with flash exception errors.
     *
     * @see Esensi\Core\Traits\RedirectingExceptionHandlerTrait
     */
    use RedirectingExceptionHandlerTrait;

}
