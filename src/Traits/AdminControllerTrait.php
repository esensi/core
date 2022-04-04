<?php

namespace Esensi\Core\Traits;

use Esensi\Core\Traits\ApiAncestryControllerTrait;
use Esensi\Core\Traits\ConfirmableControllerTrait;
use Esensi\Core\Traits\RedirectingExceptionHandlerTrait;
use Esensi\Core\Traits\ResourceControllerTrait;
use Esensi\Core\Traits\SearchableControllerTrait;

/**
 * Trait that encapsulates other admin controller related traits.
 *
 */
trait AdminControllerTrait
{
    /**
     * Allow access to the API ancestor.
     *
     * @see Esensi\Core\Traits\ApiAncestryControllerTrait
     */
    use ApiAncestryControllerTrait;

    /**
     * Make controller use confirmation modal.
     *
     * @see Esensi\Core\Traits\ConfirmableControllerTrait
     */
    use ConfirmableControllerTrait;

    /**
     * Make exceptions return a redirect with flash exception errors.
     *
     * @see Esensi\Core\Traits\RedirectingExceptionHandlerTrait
     */
    use RedirectingExceptionHandlerTrait;

    /**
     * Make controller behave like a resource controller.
     *
     * @see Esensi\Core\Traits\ResourceControllerTrait
     */
    use ResourceControllerTrait;

    /**
     * Make controller use a search modal.
     *
     * @see Esensi\Core\Traits\SearchableControllerTrait
     */
    use SearchableControllerTrait;

}
