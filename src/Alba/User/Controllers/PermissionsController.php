<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

use Alba\Core\Controllers\Controller;
use Alba\User\Controllers\PermissionsResource;
use Alba\User\Controllers\PermissionsApiController;

/**
 * Controller for accessing PermissionsResource from a web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Controllers\PermissionsResource
 * @see Alba\User\Controllers\PermissionsApiController
 */
class PermissionsController extends Controller {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'permission';

    /**
     * Inject dependencies
     *
     * @param PermissionsResource $permissionsResource
     * @param PermissionsApiController $permissionsApi
     * @return void
     */
    public function __construct(PermissionsResource $permissionsResource, PermissionsApiController $permissionsApi)
    {   
        $this->resources['permission'] = $permissionsResource;
        $this->apis['permission'] = $permissionsApi;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $paginator = $this->apis['permission']->index();
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
        $this->form('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $object = $this->apis['permission']->store();

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
        $object = $this->resources['permission']->show($id);        
        $this->content('show', ['permission' => $object]);
    }

    /**
     * Display the specified resource by name.
     *
     * @param  string  $name
     * @return void
     */
    public function showByName($name)
    {
        $object = $this->resources['permission']->showByName($name);        
        $this->content('show', ['permission' => $object]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        $object = $this->resources['permission']->show($id);
        $this->form('edit', $object);
    }

    /**
     * Show the form for modifying the specified resource.
     *
     * @param string $view
     * @param  Permission $object
     * @return void
     */
    protected function form($view, $object = null)
    {
        // Parse view data
        $data = [];
        if(!is_null($object))
        {
            $data['permission'] = $object;
        }
        $this->modal($view, $data);
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

        $object = $this->apis['permission']->update($id);

        return $this->redirect('update', ['id' => $id])
            ->with('message', $this->language('success.update'));
    }
}