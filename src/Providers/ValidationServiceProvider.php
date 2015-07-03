<?php

namespace Esensi\Core\Providers;

use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationServiceProvider as ServiceProvider;

/**
 * Service provider for custom validator.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPresenceVerifier();

        $this->app->singleton('validator', function($app)
        {
            $validator = new Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence
            // of values in a given data collection, typically a relational database or
            // other persistent data stores. And it is used to check for uniqueness.
            if (isset($app['validation.presence']))
            {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            // Add validation extensions
            $extensions = config('esensi/core::validation.extensions', []);
            foreach( $extensions as $extension => $class)
            {
                $validator->extend($extension, $class . '@validate' . ucfirst(studly_case($extension)));
                $validator->replacer($extension, $class . '@replace' . ucfirst(studly_case($extension)));
            }

            return $validator;
        });
    }

}
