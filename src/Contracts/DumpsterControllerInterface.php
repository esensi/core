<?php namespace Esensi\Core\Contracts;

/**
 * Dumpster controller interface
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface DumpsterControllerInterface{

    /**
     * Display a listing of the trashed resources.
     *
     * @return Illuminate\View\View
     */
    public function dumpster();

    /**
     * Trash the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @return Illuminate\Routing\Redirector
     */
    public function trash($id);

    /**
     * Restore the specified resource in storage.
     *
     * @param integer $id of resource to restore
     * @return Illuminate\Routing\Redirector
     */
    public function restore($id);

    /**
     * Purge the trashed resources from storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function purge();

    /**
     * Recover the trashed resources in storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function recover();

}
