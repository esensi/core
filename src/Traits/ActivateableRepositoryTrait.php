<?php

namespace Esensi\Core\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Trait implementation of activateable repository interface.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\ActivateableRepositoryInterface
 */
trait ActivateableRepositoryTrait
{
    /**
     * Activate the specified resource.
     *
     * @param integer $id of resource
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
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

        // Fire before listeners
        $this->eventUntil('activating', [ $object ] );

        // Activate the resource
        $object->active = 1;
        $object->activated_at = Carbon::now();

        // Validate the resource
        if ( $object->isInvalid('activating') || ! $object->save() )
        {
            $this->throwException($object->getErrors(), $this->error('activate'));
        }

        // Fire after listeners
        $this->eventFire('activated', [ $object ] );

        return $object;
    }

    /**
     * Set the activation status to false for resource.
     *
     * @param integer $id of resource to deactivate
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
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

        // Fire before listeners
        $this->eventUntil('deactivating', [ $object ] );

        // Deactivate the resource
        $object->active = 0;
        $object->activated_at = null;

        // Validate the resource
        if ( $object->isInvalid('deactivating') || ! $object->save() )
        {
            $this->throwException($object->getErrors(), $this->error('deactivate'));
        }

        // Fire after listeners
        $this->eventFire('deactivated', [ $object ] );

        return $object;
    }

}
