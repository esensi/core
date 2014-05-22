<?php namespace Esensi\Core\Controllers;

use \EsensiCoreApiController as ApiController;
use \Esensi\Core\Contracts\ExceptionHandlerInterface;
use \Esensi\Core\Traits\RedirectingExceptionHandlerTrait;

/**
 * Public controller for non-administrative GUIs
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Controllers\ApiController
 * @see \Esensi\Core\Traits\RedirectingExceptionHandlerTrait
 */
class PublicController extends ApiController implements
    ExceptionHandlerInterface {

    /**
     * Make exceptions return a redirect with flash exception errors
     *
     * @see \Esensi\Core\Traits\RedirectingExceptionHandlerTrait
     */
    use RedirectingExceptionHandlerTrait;

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = 'esensi::core.public.default';

}