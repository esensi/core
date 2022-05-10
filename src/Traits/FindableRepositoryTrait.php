<?php

namespace Esensi\Core\Traits;

/**
 * Trait implementation of a findable repository interface.
 *
 * @see Esensi\Core\Contracts\FindableRepositoryInterface
 */
trait FindableRepositoryTrait
{
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
     * @param  integer  $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function find($id)
    {
        return $this->read($id);
    }

    /**
     * Retrieve the specified resource from trash.
     *
     * @param  integer  $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function findInTrash($id)
    {
        return $this->retrieve($id);
    }

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param  string  $attribute to find by
     * @param  string  $value to match attribute against
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
        if (! $object)  {
            $params = ['attribute' => $attribute, 'value' => $value];
            $message = $this->error('find_by', $params);
            $this->throwException( $message );
        }

        return $object;
    }

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param  string  $attribute to find by
     * @param  array  $values to match attribute against
     * @param  boolean  $inTrash (optional)
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return array
     */
    public function findIn($attribute, array $values = [], $inTrash = false)
    {
        // Prepare a model query
        $query = $this->getModel()->query();

        // Look in trash too
        if ($inTrash) {
            $query->withTrashed();
        }

        // Get the resources
        $objects = $query->whereIn($attribute, $values)->get();

        // Throw an error if the resource could not be found
        if (! $objects)  {
            $params = ['attribute' => $attribute, 'values' => $values];
            $message = $this->error('find_in', $params);
            $this->throwException( $message );
        }

        return $objects;
    }

    /**
     * Display the specified resource with loaded relationships.
     *
     * @param  integer  $id of resource
     * @param  array  $relationship to load on resource
     * @return Esensi\Core\Models\Model
     */
    public function findWithRelated($id, array $relationship)
    {
        // Get the resource; since we're explicitly loading relationships, make sure to get a fresh copy from the DB
        $object = $this->read($id, true);

        // Throw an exception if the relationship is not related
        foreach ($relationship as $related) {
            if (!$object->isRelationship($related) && !method_exists($object, lcfirst(studly_case($related)))) {
                $this->throwException($this->error('not_related', ['relationship' => $related]) );
            }
        }

        // Load the relationships
        $object->load($relationship);
        return $object;
    }

}
