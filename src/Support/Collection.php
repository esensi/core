<?php

namespace Esensi\Core\Support;

use Esensi\Core\Traits\ParseMixedCollectionTrait;
use Illuminate\Support\Collection as BaseCollection;

/**
 * Specialized collection class based on Laravel's Illuminate\Support\Collection.
 * Provides extra utility methods an Esensi/Collection.
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
