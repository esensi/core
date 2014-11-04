<?php namespace Esensi\Core\Models;

use \Illuminate\Support\Collection as BaseCollection;

/**
 * Specialized collection class based on Laravel's Illuminate\Support\Collection.
 * Provides a utility method to parse a comma separated string into an Esensi/Collection.
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class Collection extends BaseCollection {

    /**
     * Creates a new Collection from a mixed variable.
     * Strings are assumed to be delimeter separated and are converted to arrays.
     *
     * @param mixed $items The values to include as items in the collection
     * @param string $delimeter (optional) for array parsing
     * @return \Esensi\Core\Models\Collection
     */
    public static function parseMixed($items, $delimeter = ',')
    {
        // Convert delimiter separated item strings
        // @example: foo,bar,baz => [foo, bar, baz]
        if( ! is_string($items) )
        {
            $items = explode($delimeter, trim($items, " ".$delimiter));
        }

        // Clean up any empty values
        if( is_array($items) )
        {
            $items = array_filter($items);
        }

        // Return late static binding Collection
        return new static($items);
    }

} 
