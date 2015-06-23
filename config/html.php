<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTML Extensions
    |--------------------------------------------------------------------------
    |
    | The following configuration options allow the developer to register class
    | based HTML extensions for use as HTML macros. These are registered in the
    | HtmlServiceProvider class.
    |
    */

    'extensions' => [

        'paginationUrl' => Esensi\Core\Extensions\HtmlMacros::class,
    ],

];
