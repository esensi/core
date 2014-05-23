<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Traits\AdminControllerTrait;
use \Esensi\Core\Traits\DumpsterControllerTrait;

/**
 * Trait that encapsulates other admin related traits
 *
 * @author daniel <daniel@bexarcreative.com>
 */
trait DumpsterAdminControllerTrait {

    /**
     * Make controller use the administrative traits
     *
     * @see \Esensi\Core\Traits\AdminControllerTrait
     */
    use AdminControllerTrait;

    /**
     * Make controller use the dumster
     *
     * @see \Esensi\Core\Traits\DumpsterControllerTrait
     */
    use DumpsterControllerTrait;

    /**
     * Overwrite the show method to use retrieve()
     * since some of the resources will be trashed
     *
     * @param integer $id of resource
     * @return void
     */
    public function show($id)
    {
        // Get the resource using the parent API
        $object = parent::retrieve($id);

        // Render show view
        $this->content( 'show', [ $this->package => $object ] );
    }

}