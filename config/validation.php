<?php

return [

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
        'after_other'        => 'Esensi\Core\Extensions\DateValidator',
        'before_other'       => 'Esensi\Core\Extensions\DateValidator',

        // Greater than / less than comparison based on another value
        'greater_than_other' => 'Esensi\Core\Extensions\ComparisonValidator',
        'less_than_other'    => 'Esensi\Core\Extensions\ComparisonValidator',
    ],

];
