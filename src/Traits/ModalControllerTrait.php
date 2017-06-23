<?php

namespace Esensi\Core\Traits;

/**
 * Trait for displaying forms in modals.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
trait ModalControllerTrait
{
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
        return $this->modal( 'create', $options);
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
        return $this->back('created', ['id' => $object->id])
            ->with('message', $this->message('created') );
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
        return $this->modal( 'edit', $options );
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
        return $this->back('updated', ['id' => $object->id])
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
        return $this->back( 'deleted' )
            ->with('message', $this->message('deleted') );
    }
}
