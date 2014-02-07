<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller for accessing TokensResource from a web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Resources\TokensResource
 * @see Alba\User\Controllers\TokensApiController
 */
class TokensController extends \AlbaCoreController {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'token';

    /**
     * Inject dependencies
     *
     * @param TokensResource $resource
     * @param TokensApiController $api
     * @return void
     */
    public function __construct(\AlbaTokensResource $resource, \AlbaTokensApiController $api)
    {   
        $this->setResource($resource);
        $this->setApi($api);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $paginator = $this->getApi()->index();
        $collection = $paginator->getCollection();
        $this->content('index', compact('paginator', 'collection'));
    }
    
}