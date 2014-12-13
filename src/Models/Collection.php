<?php namespace Esensi\Core\Models;

use Illuminate\Support\Collection as BaseCollection;

/**
 * Specialized collection class based on Laravel's Illuminate\Support\Collection.
 * Provides a utility method to parse a comma separated string into an Esensi/Collection.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @author diego <diego@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class Collection extends BaseCollection {

    /**
     * Creates a new Collection from a mixed variable.
     * Strings are assumed to be delimeter separated and are converted to arrays.
     *
     * @param mixed $items The values to include as items in the collection
     * @param string|array $delimiter (optional) for array parsing
     * @return Esensi\Core\Models\Collection
     */
    public static function parseMixed($items, $delimiter = ',')
    {
        // Convert delimiter separated item strings
        // @example: foo,bar,baz => [foo, bar, baz]
        if( is_string($items) )
        {
            // Removes any ',' that exist at the beginning or the end,
            // like in ',1,2,3,4,'
            $separator = '|';
            $items = str_replace($delimiter, $separator, $items);
            $items = explode($separator, trim($items, $separator));
        }

        // Put single element items in an array
        if( ! is_array($items) )
        {
            $items = [ $items ];
        }

        // Clean up any empty values
        $items = array_filter($items, function($input)
        {
            // Skip blank strings and nulls
            $isBlankString = is_string($input) && trim($input) == '';
            $isNullString = is_null($input);
            return $isNullString || $isBlankString ? false : true;
        });

        // Return late static binding collection
        return new static($items);
    }

}
