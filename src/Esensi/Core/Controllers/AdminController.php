<?php namespace Esensi\Core\Controllers;

use \EsensiCoreApiController as ApiController;
use \Esensi\Core\Contracts\ConfirmableControllerInterface;
use \Esensi\Core\Contracts\DumpsterControllerInterface;
use \Esensi\Core\Contracts\ExceptionHandlerInterface;
use \Esensi\Core\Contracts\ResourceControllerInterface;
use \Esensi\Core\Contracts\SearchableControllerInterface;
use \Esensi\Core\Traits\DumpsterAdminControllerTrait;

/**
 * Admin controller for administrative GUIs
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Controllers\ApiController
 * @see \Esensi\Core\Traits\RedirectingExceptionHandlerTrait
 */
class AdminController extends ApiController implements
    ConfirmableControllerInterface,
    DumpsterControllerInterface,
    ExceptionHandlerInterface,
    ResourceControllerInterface,
    SearchableControllerInterface {

    /**
     * Make controller use the administrative traits
     *
     * @see \Esensi\Core\Traits\DumpsterAdminControllerTrait
     */
    use DumpsterAdminControllerTrait;

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = 'esensi::core.admin.default';

    /**
     * The UI name
     * 
     * @var string
     */
    protected $ui = 'admin';

}