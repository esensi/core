<?php

namespace Esensi\Core\Models;

use Esensi\Core\Traits\ParseMixedCollectionTrait;
use Illuminate\Database\Eloquent\Collection as BaseCollection;

/**
 * Specialized collection class based on Laravel's Illuminate\Database\Eloquent\Collection.
 * Provides extra utility methods into an Esensi/Collection.
 *
 */
class Collection extends BaseCollection
{
    /**
     * Allows parsing mixed strings into a collection.
     *
     * @see Esensi\Core\Traits\ParseMixedCollectionTrait
     */
    use ParseMixedCollectionTrait;
}
