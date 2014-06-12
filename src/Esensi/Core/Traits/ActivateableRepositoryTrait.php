<?php namespace Esensi\Core\Traits;

use \Carbon\Carbon;
use \Illuminate\Support\Facades\DB;

/**
 * Trait implementation of activateable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ActivateableRepositoryInterface
 */
trait ActivateableRepositoryTrait {

    /**
     * Activate the specified resource
     *
     * @param integer $id of resource
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function activate($id)
    {
        // Get the resource
        $object = $this->find($id);

        // Make sure we can activate
        if( ! $object->isActivationAllowed() )
        {
            $this->throwException($this->error('activation_not_allowed'));
        }

        // Activate
        $object->active = 1;
        $object->activated_at = Carbon::now();

        // Activate the resource
        if ( ! $object->save() )
        {
            $this->throwException($object->getErrors(), $this->error('activate'));
        }

        return $object;
    }

    /**
     * Set the activation status to false for resource
     *
     * @param integer $id of resource to deactivate
     * @throws \Esensi\Core\Exceptions\RepositoryException
     * @return \Esensi\Core\Models\Model
     */
    public function deactivate($id)
    {
        // Get the resource
        $object = $this->find($id);

        // Make sure we can deactivate
        if( ! $object->isDeactivationAllowed() )
        {
            $this->throwException($this->error('deactivation_not_allowed'));
        }

        // Deactivate
        $object->active = 0;
        $object->activated_at = null;

        // Deactivate the resource
        if( ! $object->save() )
        {
            $this->throwException($object->getErrors(), $this->error('deactivate'));
        }

        return $object;
    }

}
