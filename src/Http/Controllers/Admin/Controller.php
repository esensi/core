<?php

namespace Esensi\Core\Http\Controllers\Admin;

use Esensi\Core\Http\Apis\Api;
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
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Http\Apis\Api
 * @see Esensi\Core\Traits\AdminControllerTrait
 */
abstract class Controller extends Api implements
    ApiAncestryControllerInterface,
    ConfirmableControllerInterface,
    ExceptionHandlerInterface,
    ResourceControllerInterface,
    SearchableControllerInterface
{
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
