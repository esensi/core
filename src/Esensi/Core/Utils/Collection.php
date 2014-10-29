<?php namespace Esensi\Core\Utils;

use Illuminate\Support\Collection as BaseCollection;

/**
 * Specialized collection class based on Laravel's Illuminate\Support\Collection.
 * Provides a utility method to parse a comma separated string into an Esensi/Collection.
 *
 * @package Esensi\Core\Utils
 * @author diego <diego@emersonmedia.com>
 */
class Collection extends BaseCollection {

    /**
     * Returns a new Esensi/Collection. If the $input is a string, assumes it's a comma separated list of values and
     * creates the collection using each of those exploded values.
     *
     * @param string|array $input The values to include as items in the collection
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public static function parseMixed($input)
    {
        if (is_string($input))
        {
            $input = explode(',', $input);
        }
        if (!is_array($input))
        {
            throw new \InvalidArgumentException("The 'input' parameter must be a string or an array.");
        }
        return new Collection($input);
    }

} 