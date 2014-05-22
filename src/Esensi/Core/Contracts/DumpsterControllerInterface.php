<?php namespace Esensi\Core\Contracts;

/**
 * Dumpster controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface DumpsterControllerInterface{

    /**
     * Display a listing of the trashed resources.
     *
     * @return void
     */
    public function dumpster();

    /**
     * Trash the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @return \Illuminate\Routing\Redirector
     */
    public function trash($id);

    /**
     * Restore the specified resource in storage.
     *
     * @param integer $id of resource to restore
     * @return \Illuminate\Routing\Redirector
     */
    public function restore($id);

    /**
     * Purge the trashed resources from storage.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function purge();

    /**
     * Recover the trashed resources in storage.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function recover();

}