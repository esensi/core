<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Traits\ModeledRepositoryTrait;

/**
 * Trait implementation of CRUD repository
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\CruddableRepositoryInterface
 * @see \Esensi\Core\Traits\ModeledRepositoryTrait
 */
trait CruddableRepositoryTrait{

    /**
     * Make this repository use active record models
     *
     * @see \Esensi\Core\Traits\ModeledRepositoryTrait
     */
    use ModeledRepositoryTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes to store on the resource
     * @return object
     */
    public function create(array $attributes)
    {
        return $this->getModel()
            ->fill($attributes)
            ->save();
    }

    /**
     * Read the specified resource from storage.
     *
     * @param integer $id of resource
     * @return object
     */
    public function read(integer $id)
    {
        return $this->getModel()
            ->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @param array $attributes to update on the resource
     * @return object
     */
    public function update(integer $id, array $attributes)
    {
        return $this->show($id)
            ->fill($attributes)
            ->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return boolean
     */
    public function delete(integer $id)
    {
        $model = $this->show($id);
        
        // Force deletes on soft-deleted models
        if( method_exists( $model, 'isSoftDeleting' ) && $model->isSoftDeleting() )
        {
            return $model->forceDelete();
        }

        return $model->delete();
    }

}