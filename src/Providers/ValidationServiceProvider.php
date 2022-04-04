<?php

namespace Esensi\Core\Providers;

use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationServiceProvider as ServiceProvider;

/**
 * Service provider for custom validator.
 *
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
            if (isset($app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            // Add validation extensions
            $extensions = config('esensi/core::validation.extensions', []);
            foreach ($extensions as $extension => $class) {
                $method = ucfirst(studly_case($extension));
                $validator->extend($extension, $class . '@validate' . $method);
                $validator->replacer($extension, $class . '@replace' . $method);
            }

            // Add validation implicit extensions
            $extensions = config('esensi/core::validation.implicit_extensions', []);
            foreach ($extensions as $extension => $class) {
                $method = ucfirst(studly_case($extension));
                $validator->extendImplicit($extension, $class . '@validate' . $method);
                $validator->replacer($extension, $class . '@replace' . $method);
            }

            return $validator;
        });
    }

}
