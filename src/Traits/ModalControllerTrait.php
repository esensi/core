<?php namespace Esensi\Core\Traits;

/**
 * Trait for displaying forms in modals
 *
 * @author daniel <daniel@bexarcreative.com>
 */
trait ModalControllerTrait{

    /**
     * Display a create form for the specified resource.
     *
     * @return void
     */
    public function create()
    {
        // Get the form options
        $options = method_exists($this, 'formOptions') ? $this->formOptions() : [];

        // Render create view
        $this->modal( 'create', $options);
    }

    /**
     * Display an edit form for the specified resource.
     *
     * @param integer $id of resource
     * @return void
     */
    public function edit($id)
    {
        // Get the resource
        $object = $this->api()->show($id);

        // Get the form options
        $options = method_exists($this, 'formOptions') ? $this->formOptions($object) : [ $this->package => $object ];

        // Render edit view
        $this->modal( 'edit', $options );
    }

}
