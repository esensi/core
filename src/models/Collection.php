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
     * @param string $delimiter (optional) for array parsing
     * @return \Esensi\Core\Models\Collection
     */
    public static function parseMixed($items, $delimiter = ',')
    {
        // Convert delimiter separated item strings
        // @example: foo,bar,baz => [foo, bar, baz]
        if( is_string($items) )
        {
            // Removes any ',' that exist at the beginning or the end,
            // like in ',1,2,3,4,'.
            $items = explode($delimiter, trim($items, $delimiter));
        }

        if (! is_array($items) )
        {
            $items = [$items];
        }

        // Clean up any empty values
        $items = array_filter($items, function($input)
        {
            // null and blank strings are the only ones skipped
            if (
                (is_null($input)) or
                (is_string($input) and trim($input) == '' )
            ) {
                return false;
            }

            return true;

        });


        // Return late static binding Collection
        return new static($items);

    }

}
