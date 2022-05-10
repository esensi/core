<?php

namespace Esensi\Core\Contracts;

/**
 * Resource controller interface
 *
 */
interface ResourceControllerInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function index();

    /**
     * Display a create form for the specified resource.
     *
     * @return Illuminate\View\View
     */
    public function create();

    /**
     * Store a newly created resource in storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function store();

    /**
     * Display the specified resource.
     *
     * @param  integer  $id of resource
     * @return Illuminate\View\View
     */
    public function show($id);

    /**
     * Display an edit form for the specified resource.
     *
     * @param  integer  $id of resource
     * @return Illuminate\View\View
     */
    public function edit($id);

    /**
     * Update the specified resource in storage.
     *
     * @param  integer  $id of resource to update
     * @return Illuminate\Routing\Redirector
     */
    public function update($id);

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id of resource to remove
     * @return Illuminate\Routing\Redirector
     */
    public function delete($id);

    /**
     * Alias for delete method
     *
     * @param  integer  $id of resource to remove
     * @return Illuminate\Routing\Redirector
     */
    public function destroy($id);

    /**
     * Truncates the resources from storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function truncate();

}
