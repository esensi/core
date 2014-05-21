<?php namespace Esensi\Core\Controllers;

use \Esensi\Core\Controllers\ApiController;
use \Esensi\Core\Exceptions\RepositoryException;
use \Esensi\Core\Traits\RedirectingExceptionHandlerTrait;

use \Illuminate\Support\Facades\App;

/**
 * Public controller for non-administrative GUIs
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Controllers\ApiController
 * @see \Esensi\Core\Traits\RedirectingExceptionHandlerTrait
 */
class PublicController extends ApiController {

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