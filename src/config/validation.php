<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration values for Esensi\Core components package
    |--------------------------------------------------------------------------
    |
    | The following lines contain the default configuration values for the
    | Esensi\Core components package. You can publish these to your project for
    | modification using the following Artisan command:
    |
    | php artisan config:publish esensi/core
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Validation Extensions
    |--------------------------------------------------------------------------
    |
    | The following configuration options allow the developer to register class
    | based Validator extensions for use in input and model validation. These
    | are registered in the ValidationServiceProvider class.
    |
    */

    'extensions' => [

        // Before / after date comparison based on another value
        'after_other'        => 'Esensi\Core\Validators\DateValidator',
        'before_other'       => 'Esensi\Core\Validators\DateValidator',

        // Greater than / less than comparison based on another value
        'greater_than_other' => 'Esensi\Core\Validators\ComparisonValidator',
        'less_than_other'    => 'Esensi\Core\Validators\ComparisonValidator',
    ],

];
