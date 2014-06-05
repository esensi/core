<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration values for Esensi\Build components package
    |--------------------------------------------------------------------------
    |
    | The following lines contain the default configuration values for the
    | Esensi\Build components package. You can publish these to your project for
    | modification using the following Artisan command:
    |
    | php artisan config:publish esensi/core
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Application aliases
    |--------------------------------------------------------------------------
    |
    | The following configuration options allow the developer to map aliases to
    | controllers and models for easier customization of how Esensi handles
    | requests related to this components package. These aliases are loaded by
    | the service provider for this components package.
    |
    */

    'aliases' => [
        'EsensiBuildCommand'        => '\Esensi\Build\Commands\BuildCommand',
        'EsensiBuildWatchCommand'   => '\Esensi\Build\Commands\BuildWatchCommand',
        'EsensiBuildCleanCommand'   => '\Esensi\Build\Commands\BuildCleanCommand',
        'EsensiBuildStylesCommand'  => '\Esensi\Build\Commands\BuildStylesCommand',
        'EsensiBuildScriptsCommand' => '\Esensi\Build\Commands\BuildScriptsCommand',
        'EsensiBuildImagesCommand'  => '\Esensi\Build\Commands\BuildImagesCommand',
        'EsensiBuildFontsCommand'   => '\Esensi\Build\Commands\BuildFontsCommand',
        'EsensiBuildLintCommand'    => '\Esensi\Build\Commands\BuildLintCommand',
    ],

    /*
    |--------------------------------------------------------------------------
    | Gulp Binary Location
    |--------------------------------------------------------------------------
    |
    | The following configuration option sets where the "gulp" command can be
    | found. For a local install it defaults to node_modules/.bin/gulp however
    | if installed globally you can set it with this option.
    |
    */

    'binary' => null,

    /*
    |--------------------------------------------------------------------------
    | Production Environments
    |--------------------------------------------------------------------------
    |
    | The following configuration options set which environments should be
    | treated as "production" environments. In these environments the build
    | commands will always run with the --production switch.
    |
    */

    'environments' => [
        'production'
    ],

    /*
    |--------------------------------------------------------------------------
    | Builds Directories
    |--------------------------------------------------------------------------
    |
    | The following configuration option sets where the builds should be stored.
    | The base path is relative to the public directory while the other paths
    | are relative to the base path. If you make changes here be sure to change
    | the same configurations in the Gulpfile.js.
    |
    */

    'directories' => [
        'base'    => 'builds',
        'fonts'   => 'fonts',
        'images'  => 'images',
        'scripts' => 'scripts',
        'styles'  => 'styles',
    ],

];