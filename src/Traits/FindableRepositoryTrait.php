<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of a findable repository interface.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\FindableRepositoryInterface
 */
trait FindableRepositoryTrait {

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function all()
    {
        return $this->getModel()->all();
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function find($id)
    {
        return $this->read($id);
    }

    /**
     * Retrieve the specified resource from trash.
     *
     * @param integer $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function findInTrash($id)
    {
        return $this->retrieve($id);
    }

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param string $attribute to find by
     * @param string $value to match attribute against
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function findBy($attribute, $value)
    {
        // Get the resource
        $object = $this->getModel()
            ->where($attribute, $value)
            ->first();

        // Throw an error if the resource could not be found
        if( ! $object )
        {
            $params = ['attribute' => $attribute, 'value' => $value];
            $message = $this->error('find_by', $params);
            $this->throwException( $message );
        }

        return $object;
    }

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param string $attribute to find by
     * @param array $values to match attribute against
     * @param boolean $inTrash (optional)
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return array
     */
    public function findIn($attribute, array $values = [], $inTrash = false)
    {
        // Prepare a model query
        $query = $this->getModel()->query();

        // Look in trash too
        if( $inTrash )
        {
            $query->withTrashed();
        }

        // Get the resources
        $objects = $query->whereIn($attribute, $values)->get();

        // Throw an error if the resource could not be found
        if( ! $objects )
        {
            $params = ['attribute' => $attribute, 'values' => $values];
            $message = $this->error('find_in', $params);
            $this->throwException( $message );
        }

        return $objects;
    }

    /**
     * Display the specified resource with loaded relationships.
     *
     * @param integer $id of resource
     * @param array $relationship to load on resource
     * @return Esensi\Core\Models\Model
     */
    public function findWithRelated($id, array $relationship)
    {
        // Get the resource
        $object = $this->read($id);

        // Throw an exception if the relationship is not related
        foreach($relationship as $related)
        {
            if( ! $object->isRelationship($related) )
            {
                $this->throwException( $this->error('not_related', ['relationship' => $related]) );
            }
        }

        // Load the relationships
        $object->load($relationship);
        return $object;
    }

}
