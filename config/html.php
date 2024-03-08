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

    /*
    |--------------------------------------------------------------------------
    | Asset Compilation Tool
    |--------------------------------------------------------------------------
    |
    | This option controls the asset compilation tool the application will use
    | for processing and bundling its assets.
    |
    | Supported: "gulp", "webpack"
    |
    */

    'asset_compiler' => "webpack",

];
