<?php namespace Esensi\Core\Controllers;

use \EsensiCoreApiController as Controller;
use \Esensi\Core\Contracts\ApiAncestryControllerInterface;
use \Esensi\Core\Contracts\ExceptionHandlerInterface;
use \Esensi\Core\Traits\PublicControllerTrait;

/**
 * Public controller for non-administrative GUIs
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Controllers\ApiController
 * @see \Esensi\Core\Traits\PublicControllerTrait
 */
class PublicController extends Controller implements
    ApiAncestryControllerInterface,
    ExceptionHandlerInterface {

    /**
     * Make controller use the public traits
     *
     * @see \Esensi\Core\Traits\PublicControllerTrait
     */
    use PublicControllerTrait;

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = 'esensi/core::core.public.default';

}
