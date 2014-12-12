<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Traits\ApiAncestryControllerInterface;
use \Esensi\Core\Traits\RedirectingExceptionHandlerTrait;

/**
 * Trait that encapsulates other public controller related traits
 *
 * @author daniel <daniel@bexarcreative.com>
 */
trait PublicControllerTrait {

    /**
     * Allow access to the API ancestor
     *
     * @see \Esensi\Core\Traits\ApiAncestryControllerInterface
     */
    use ApiAncestryControllerInterface;

    /**
     * Make exceptions return a redirect with flash exception errors
     *
     * @see \Esensi\Core\Traits\RedirectingExceptionHandlerTrait
     */
    use RedirectingExceptionHandlerTrait;

}
