<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing PermissionsResource from a backend web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\User\Controllers\PermissionsController
 */
class PermissionsAdminController extends \AlbaPermissionsController {
    
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';
}