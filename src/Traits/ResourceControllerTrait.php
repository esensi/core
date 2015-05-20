<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of resource controller interface.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\ResourceControllerInterface
 */
trait ResourceControllerTrait {

	/**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        // Get the paginator using the parent API
        $paginator = $this->api()->index();

        // Show collection as a paginated table
        $collection = $paginator->getCollection();
        return $this->content('index', compact('paginator', 'collection'));
    }

    /**
     * Display a create form for the specified resource.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        // Get the form options
        $options = method_exists($this, 'formOptions') ? $this->formOptions() : [];

        // Render create view
        return $this->content( 'create', $options);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function store()
    {
        // Use the parent API to save the resource
        $object = $this->api()->store();

        // Redirect back with message
        return $this->redirect('created', ['id' => $object->id])
            ->with('message', $this->message('created') );
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        // Get the resource using the parent API
        $object = $this->api()->show($id);

        // Render show view
        return $this->content( 'show', [ $this->package => $object ] );
    }

    /**
     * Display an edit form for the specified resource.
     *
     * @param integer $id of resource
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        // Get the resource
        $object = $this->api()->show($id);

        // Get the form options
        $options = method_exists($this, 'formOptions') ? $this->formOptions($object) : [ $this->package => $object ];

        // Render edit view
        return $this->content( 'edit', $options );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @return Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        // Use the parent API to update the resource
        $object = $this->api()->update($id);

        // Redirect back with message
        return $this->redirect('updated', ['id' => $object->id])
            ->with('message', $this->message('updated') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        // Use the parent API to remove the resource
        $response = $this->api()->delete($id);

        // Redirect back with message
        return $this->redirect( 'deleted' )
            ->with('message', $this->message('deleted') );
    }

    /**
     * Alias for delete method
     *
     * @param integer $id of resource to remove
     * @return Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        return $this->delete($id);
    }

    /**
     * Truncates the resources from storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function truncate()
    {
        // Use the parent API to truncate the resources
        $response = $this->api()->truncate();

        // Redirect back with message
        return $this->back('truncated' )
            ->with('message', $this->message('truncated') );
    }

}
