<?php namespace Esensi\Core\Controllers;

/**
 * Controller for accessing Resource from an Admin interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Controllers\Controller
 */
class AdminController extends \EsensiCoreController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'esensi::core.admin.default';

    /**
     * The UI name
     * 
     * @var string
     */
    protected $ui = 'admin';
}