<?php namespace Esensi\Core\Providers;

use \Esensi\Core\Providers\PackageServiceProvider;
use \Esensi\Core\Validators\ComparisonValidator;
use \Esensi\Core\Validators\DateValidator;

/**
 * Service provider for custom validators
 *
 * @author Daniel LaBarge <dalabarge@emersonmedia.com>
 */
class ValidatorServiceProvider extends PackageServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Bind resolver for custom validators
        $validator = $this->app->make('validator');

        // Add less comparison validation rule
        $validator->extend('less', 'Esensi\Core\Validators\ComparisonValidator@validateLess');
        $validator->replacer('less', 'Esensi\Core\Validators\ComparisonValidator@replaceLess');

        // Add greater comparison validation rule
        $validator->extend('greater', 'Esensi\Core\Validators\ComparisonValidator@validateGreater');
        $validator->replacer('greater', 'Esensi\Core\Validators\ComparisonValidator@replaceGreater');

        // Add before_other date validation rule
        $validator->extend('before_other', 'Esensi\Core\Validators\DateValidator@validateBeforeOther');
        $validator->replacer('before_other', 'Esensi\Core\Validators\DateValidator@replaceBeforeOther');

        // Add after_other date validation rule
        $validator->extend('after_other', 'Esensi\Core\Validators\DateValidator@validateAfterOther');
        $validator->replacer('after_other', 'Esensi\Core\Validators\DateValidator@replaceAfterOther');
    }

}
