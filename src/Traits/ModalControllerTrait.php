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

}
