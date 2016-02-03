<?php

namespace Esensi\Core\Traits;

/**
 * Specialized collection trait based on Laravel's Illuminate\Support\Collection.
 * Provides a utility method to parse a comma separated string into a collection.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @author Diego Caprioli <diego@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
trait ParseMixedCollectionTrait
{
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
