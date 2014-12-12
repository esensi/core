<?php namespace Esensi\Core\Traits;

use \Illuminate\Support\Facades\App;

/**
 * Trait implementation of API ancestry controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ApiAncestryControllerInterface
 */
trait ApiAncestryControllerTrait{

    /**
     * Get the API ancestor controller class
     * of the current controller class.
     *
     * @return \Esensi\Core\Controllers\ApiController
     */
    public function api()
    {
        // Make a copy of the parent class
        $class = get_parent_class();
        $parent = App::make($class);

        // Return first ApiController ancestor found
        if( str_contains($class, 'ApiController'))
        {
            return $parent;
        }

        // Recursively look up the parent class
        if( method_exists($parent, 'api') )
        {
            return $parent->api();
        }

        // Return the parent class found already
        return $parent;
    }

}
