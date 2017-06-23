<?php

namespace Esensi\Core\Traits;

use Esensi\Contracts\PackagedInterface;
use Esensi\Contracts\RepositoryInjectedInterface;
use Illuminate\Support\Facades\App;

/**
 * Trait implementation of API ancestry controller interface.
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Esensi\Core\Contracts\ApiAncestryControllerInterface
 */
trait ApiAncestryControllerTrait
{
    /**
     * Get the API ancestor controller class
     * of the current controller class.
     *
     * @return \Esensi\Core\Http\Apis\Api
     */
    public function api()
    {
        // Make a copy of the parent class
        $class = get_parent_class();
        $parent = App::make($class);

        // Copy over the packaged properties
        if( $this instanceof PackagedInterface )
        {
            $parent->setUI( $this->getUI() );
            $parent->setPackage( $this->getPackage() );
            $parent->setNamespacing( $this->getNamespacing() );
        }

        // Copy over the injected repositories
        if( $this instanceof RepositoryInjectedInterface )
        {
            foreach($this->repositories as $name => $repository)
            {
                $parent->setRepository($repository, $name);
            }
        }

        // Return first ApiController ancestor found
        if( str_contains($class, 'Api'))
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
