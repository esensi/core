<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

use Alba\Core\Controllers\Controller;
use Alba\User\Controllers\RolesResource;
use Alba\User\Controllers\RolesApiController;

/**
 * Controller for accessing RolesResource from a web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Controllers\RolesResource
 * @see Alba\User\Controllers\RolesApiController
 */
class RolesController extends Controller {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'role';

    /**
     * Inject dependencies
     *
     * @param RolesResource $rolesResource
     * @param RolesApiController $rolesApi
     * @return void
     */
    public function __construct(RolesResource $rolesResource, RolesApiController $rolesApi)
    {   
        $this->resources['role'] = $rolesResource;
        $this->apis['role'] = $rolesApi;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $paginator = $this->apis['role']->index();
        $collection = $paginator->getCollection();
        $this->content('index', compact('paginator', 'collection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $this->content('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $object = $this->apis['role']->store();

        return $this->redirect('store', ['id' => $object->id])
            ->with('message', $this->language('success.store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        $object = $this->resources['role']->show($id);        
        $this->content('show', ['role' => $object]);
    }

    /**
     * Display the specified resource by name.
     *
     * @param  string  $name
     * @return void
     */
    public function showByName($name)
    {
        $object = $this->resources['role']->showByName($name);        
        $this->content('show', ['role' => $object]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        $object = $this->resources['role']->show($id);
        $this->content('edit', ['role' => $object]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        // @todo what about security here?

        $object = $this->apis['role']->update($id);

        return $this->redirect('update', ['id' => $id])
            ->with('message', $this->language('success.update'));
    }
}