<?php

namespace Esensi\Core\Traits;

use Esensi\Core\Models\Collection;

trait EsensiModelCollectionTrait
{
    /**
     * Converts all returned eloquent collections from Illuminate\Database\Eloquent\Collection
     * into Esensi\Core\Models\Collection.  This allows for extra functionality.
     *
     * @param array $models An array of models to turn into a collection.
     *
     * @return \Esensi\Core\Models\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }
}