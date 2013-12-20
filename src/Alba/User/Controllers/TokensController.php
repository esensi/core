<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

use Alba\Core\Controllers\Controller;

/**
 * Controller for accessing TokensResource from a web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Resources\TokensResource
 * @see Alba\User\Controllers\TokensApiController
 */
class TokensController extends Controller {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'token';

    /**
     * Inject dependencies
     *
     * @param TokensResource $tokensResource
     * @param TokensApiController $tokensApi
     * @return void
     */
    public function __construct(\AlbaTokensResource $tokensResource, \AlbaTokensApiController $tokensApi)
    {   
        $this->resources['token'] = $tokensResource;
        $this->apis['token'] = $tokensApi;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $paginator = $this->apis['token']->index();
        $collection = $paginator->getCollection();
        $this->content('index', compact('paginator', 'collection'));
    }
    
}