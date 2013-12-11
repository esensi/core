<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;
use Alba\Core\Controllers\Controller;
use Alba\User\Controllers\TokensResource;

/**
 * Controller for accessing TokensResource as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Controllers\TokensResource
 */
class TokensApiController extends Controller {

    /**
     * Inject dependencies
     *
     * @param TokensResource $tokensResource;
     * @return void
     */
	public function __construct(TokensResource $tokensResource)
	{
		$this->resources['token'] = $tokensResource;
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
        if( $types = Input::get('types', false) )
        {
            $types = is_array($types) ? $types : explode(',', $types);
            $types = array_values($types);
            $test = implode('', $types);
            if(!empty($test))
            {
                $params['types'] = $types;
                $params['scopes']['ofType'] = [ $types ];
            }
        }

        return $this->resources['token']->index($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $attributes = Input::all();
        return $this->resources['token']->store($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->resources['token']->show($id);
    }

    /**
     * Display the specified resource by token.
     *
     * @param string $token of object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function showByToken($token)
    {
        return $this->resources['token']->showByToken($token);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id)
    {
        $attributes = Input::all();
        $object = $this->resources['token']->update($id, $attributes);
        return $object;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id of object to remove
     * @param bool $force delete
     * @return bool
     * 
     */
    public function destroy($id)
    {
        return $this->resources['token']->destroy($id);
    }

}