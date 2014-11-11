<?php namespace Esensi\Core\Providers;

use \Illuminate\Validation\ValidationServiceProvider as ServiceProvider;
use \Illuminate\Validation\Factory;
use \Esensi\Core\Validators\ComparisonValidator;
use \Esensi\Core\Validators\DateValidator;

/**
 * Service provider for custom validator.
 *
 * @author Daniel LaBarge <dalabarge@emersonmedia.com>
 */
class ValidationServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPresenceVerifier();

        $this->app->bindShared('validator', function($app)
        {
            $validator = new Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence
            // of values in a given data collection, typically a relational database or
            // other persistent data stores. And it is used to check for uniqueness.
            if (isset($app['validation.presence']))
            {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            // Add less_than_other comparison validation rule
            $validator->extend('less_than_other', 'Esensi\Core\Validators\ComparisonValidator@validateLessThanOther');
            $validator->replacer('less_than_other', 'Esensi\Core\Validators\ComparisonValidator@replaceLessThanOther');

            // Add greater_than_other comparison validation rule
            $validator->extend('greater_than_other', 'Esensi\Core\Validators\ComparisonValidator@validateGreaterThanOther');
            $validator->replacer('greater_than_other', 'Esensi\Core\Validators\ComparisonValidator@replaceGreaterThanOther');

            // Add before_other date validation rule
            $validator->extend('before_other', 'Esensi\Core\Validators\DateValidator@validateBeforeOther');
            $validator->replacer('before_other', 'Esensi\Core\Validators\DateValidator@replaceBeforeOther');

            // Add after_other date validation rule
            $validator->extend('after_other', 'Esensi\Core\Validators\DateValidator@validateAfterOther');
            $validator->replacer('after_other', 'Esensi\Core\Validators\DateValidator@replaceAfterOther');

            return $validator;
        });
    }

}
