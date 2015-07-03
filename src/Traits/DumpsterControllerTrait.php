<?php

namespace Esensi\Core\Traits;

use Illuminate\Support\Facades\Input;

/**
 * Trait implementation of dumpster controller interface.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\DumpsterControllerInterface
 */
trait DumpsterControllerTrait
{
    /**
     * Display a listing of the trashed resources.
     *
     * @return Illuminate\View\View
     */
    public function dumpster()
    {
        Input::merge(['trashed' => 'only']);
        return $this->index();
    }

    /**
     * Trash the specified resource in storage.
     *
     * @param integer $id of resource to trash
     * @return Illuminate\Routing\Redirector
     */
    public function trash($id)
    {
        // Use the parent API to trash the resource
        $response = $this->api()->trash($id);

        // Redirect back with message
        return $this->back( 'trashed' )
            ->with('message', $this->message('trashed') );
    }

    /**
     * Restore the specified resource in storage.
     *
     * @param integer $id of resource to restore
     * @return Illuminate\Routing\Redirector
     */
    public function restore($id)
    {
        // Use the parent API to restore the resource
        $response = $this->api()->restore($id);

        // Redirect back with message
        return $this->back( 'restored' )
            ->with('message', $this->message('restored') );
    }

    /**
     * Purge the trashed resources from storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function purge()
    {
        // Use the parent API to purge the resource
        $response = $this->api()->purge();

        // Redirect back with message
        return $this->back( 'purged' )
            ->with('message', $this->message('purged') );
    }

    /**
     * Recover the trashed resources in storage.
     *
     * @return Illuminate\Routing\Redirector
     */
    public function recover()
    {
        // Use the parent API to recover the resource
        $response = $this->api()->recover();

        // Redirect back with message
        return $this->back( 'recovered' )
            ->with('message', $this->message('recovered') );
    }

}
