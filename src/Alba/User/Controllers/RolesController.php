<?php namespace Alba\User\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller for accessing RolesResource from a web interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Controller
 * @see Alba\User\Resources\RolesResource
 * @see Alba\User\Controllers\RolesApiController
 */
class RolesController extends \AlbaCoreController {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'role';

    /**
     * Inject dependencies
     *
     * @param RolesResource $resource
     * @param RolesApiController $api
     * @return void
     */
    public function __construct(\AlbaRolesResource $resource, \AlbaRolesApiController $api)
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
        $object = $this->getApi()->store();

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
        $object = $this->getApi()->show($id);        
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
        $object = $this->getApi()->showByName($name);        
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
        $object = $this->getApi()->show($id);
        $this->form('edit', $object);
    }

    /**
     * Show the form for modifying the specified resource.
     *
     * @param string $view
     * @param  Role $object
     * @return void
     */
    protected function form($view, $object = null)
    {
        // Get options
        $permissionsOptions = $this->getResource()->getModel('permission')->listAlphabetically();
        $permissions = isset($object) ? $object->perms->lists('id') : [];

        // Parse view data
        $data = compact('permissionsOptions', 'permissions');
        if(!is_null($object))
        {
            $data['role'] = $object;
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

        $object = $this->getApi()->update($id);

        return $this->redirect('update', ['id' => $id])
            ->with('message', $this->language('success.update'));
    }
}