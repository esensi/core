<?php namespace Esensi\Core\Http\Controllers;

use App\Http\Controllers\ApiController as Controller;
use Esensi\Core\Contracts\ApiAncestryControllerInterface;
use Esensi\Core\Contracts\ConfirmableControllerInterface;
use Esensi\Core\Contracts\ExceptionHandlerInterface;
use Esensi\Core\Contracts\ResourceControllerInterface;
use Esensi\Core\Contracts\SearchableControllerInterface;
use Esensi\Core\Traits\AdminControllerTrait;

/**
 * Admin controller for administrative UIs
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Http\Controllers\ApiController
 * @see Esensi\Core\Traits\AdminControllerTrait
 */
abstract class AdminController extends Controller implements
    ApiAncestryControllerInterface,
    ConfirmableControllerInterface,
    ExceptionHandlerInterface,
    ResourceControllerInterface,
    SearchableControllerInterface {

    /**
     * Make controller use the administrative traits
     *
     * @see Esensi\Core\Traits\AdminControllerTrait
     */
    use AdminControllerTrait;

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = 'esensi/core::core.admin.default';

    /**
     * The UI name
     *
     * @var string
     */
    protected $ui = 'admin';

}
