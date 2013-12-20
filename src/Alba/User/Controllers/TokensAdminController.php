<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing TokensResource from a backend web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\TokensController
 */
class TokensAdminController extends \AlbaTokensController {
    
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';
}