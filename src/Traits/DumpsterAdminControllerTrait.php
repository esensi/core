<?php

namespace Esensi\Core\Traits;

use Esensi\Core\Traits\AdminControllerTrait;
use Esensi\Core\Traits\DumpsterControllerTrait;
use Illuminate\Support\Str;

/**
 * Trait that encapsulates other admin related traits.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
trait DumpsterAdminControllerTrait
{
    /**
     * Make controller use the administrative traits.
     *
     * @see Esensi\Core\Traits\AdminControllerTrait
     */
    use AdminControllerTrait;

    /**
     * Make controller use the dumster.
     *
     * @see Esensi\Core\Traits\DumpsterControllerTrait
     */
    use DumpsterControllerTrait;

    /**
     * Overwrite the show method to use retrieve()
     * since some of the resources will be trashed.
     *
     * @param integer $id of resource
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        // Get the resource using the parent API
        $object = $this->api()->retrieve($id);

        // Render show view
        $data = [ Str::camel($this->package) => $object ];
        return $this->content( 'show', $data );
    }

}
