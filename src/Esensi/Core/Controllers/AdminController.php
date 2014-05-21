<?php namespace Esensi\Core\Controllers;

use \Esensi\Core\Controllers\ApiController;
use \Esensi\Core\Traits\RedirectingExceptionHandlerTrait;

/**
 * Admin controller for administrative GUIs
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Controllers\ApiController
 * @see \Esensi\Core\Traits\RedirectingExceptionHandlerTrait
 */
class AdminController extends ApiController {

    /**
     * Make exceptions return a redirect with flash exception errors
     *
     * @see \Esensi\Core\Traits\RedirectingExceptionHandlerTrait
     */
    use RedirectingExceptionHandlerTrait;

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = 'esensi::core.admin.default';

    /**
     * The UI name
     * 
     * @var string
     */
    protected $ui = 'admin';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the paginator using the parent API
        $paginator = parent::index();

        // Render index view
        return $this->content( 'index', $paginator->toArray() );
    }

    /**
     * Display a search form for the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function search()
    {
        // Render search view
        return $this->modal( 'search' );
    }

    /**
     * Display a create form for the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Render create view
        return $this->content( 'create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        // Use the parent API to save the resource
        $response = parent::store();

        // Redirect back with message
        return $this->back( 'store', $this->message('stored') );
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return \Illuminate\View\View
     */
    public function show(integer $id)
    {
        // Get the resource using the parent API
        $object = parent::show($id);

        // Render show view
        return $this->content( 'show', $object->toArray() );
    }

    /**
     * Display an edit form for the specified resource.
     *
     * @param integer $id of resource
     * @return \Illuminate\View\View
     */
    public function edit(integer $id)
    {
        // Get the resource
        $object = $this->show($id);

        // Render edit view
        return $this->content( 'edit', $object->toArray() );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(integer $id)
    {
        // Use the parent API to update the resource
        $response = parent::update($id);

        // Redirect back with message
        return $this->back( 'update', $this->message('updated') );
    }

    /**
     * Display a confirmation form for the specified resource action.
     *
     * @param string $action
     * @return \Illuminate\View\View
     */
    public function confirm(string $action)
    {
        // Get the resource
        $object = $this->show($id);

        // Render confirmation modal
        return $this->modal( 'confirm_' . $action, $object->toArray() );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return boolean
     * 
     */
    public function destroy(integer $id)
    {
        // Use the parent API to remove the resource
        $response = parent::destroy($id);

        // Redirect back with message
        return $this->back( 'destroy', $this->message('destroyed') );
    }

}