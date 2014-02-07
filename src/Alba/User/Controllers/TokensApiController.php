<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing TokensResource as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\ApiController
 * @see Alba\User\Resources\TokensResource
 */
class TokensApiController extends \AlbaCoreApiController {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'token';
    
    /**
     * Inject dependencies
     *
     * @param TokensResource $resource;
     * @return void
     */
	public function __construct(\AlbaTokensResource $resource)
	{
		$this->setResource($resource);
	}

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $params = Input::only('max', 'order', 'sort', 'keywords');

        // Filter by type
        $this->setupArrayTypeScope($params, 'types', 'ofType');

        return $this->getResource()->index($params);
    }

    /**
     * Display the specified resource by token.
     *
     * @param string $token of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showByToken($token)
    {
        return $this->getResource()->showByToken($token);
    }
}