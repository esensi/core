<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration values for Esensi\Activity components package
    |--------------------------------------------------------------------------
    |
    | The following lines contain the default configuration values for the
    | Esensi\Activity components package. You can publish these to your project for
    | modification using the following Artisan command:
    |
    | php artisan config:publish esensi/activity
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
    | the service provider for this components package. If your app actually
    | makes use of a class by the same alias then simply comment out the
    | alias here so that the local class may be used instead.
    |
    */

    'aliases'   => [
        'App\Exceptions\ActivityRepositoryException'    => Esensi\Activity\Exceptions\ActivityRepositoryException::class,
        'App\Http\Controllers\Admin\ActivityController' => Esensi\Activity\Http\Controllers\Admin\ActivityController::class,
        'App\Http\Apis\ActivityApi'                     => Esensi\Activity\Http\Apis\ActivityApi::class,
        'App\Http\Controllers\ActivityController'       => Esensi\Activity\Http\Controllers\ActivityController::class,
        'App\Http\Middleware\ExcessiveQueryLogger'      => Esensi\Activity\Http\Middleware\ExcessiveQueryLogger::class,
        'App\Models\Activity'                           => Esensi\Activity\Models\Activity::class,
        'App\Models\Activity\Bag'                       => Esensi\Activity\Models\Bag::class,
        'App\Models\Observers\ActivityObserver'         => Esensi\Activity\Models\Observers\ActivityObserver::class,
        'App\Repositories\ActivityRepository'           => Esensi\Activity\Repositories\ActivityRepository::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration of component package route prefixes
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter the route prefixes used for
    | the administrative backend, API, and component package URLs.
    |
    */

    'prefixes'  => [
        'admin'  => 'activities',
        'public' => 'activities',
        'api'    => 'activities',
    ],

    /*
    |--------------------------------------------------------------------------
    | Interfaces to be enabled by packages
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter which interfaces are included,
    | effectively allowing the developer to not use some or all of the default
    | interfaces available. This is used primarily by the routes configurations.
    |
    */

    // UIs
    'ui'        => [
        'admin'  => true,
        'public' => true,
    ],

    // APIs
    'api'       => [
        'public' => true,
        'admin'  => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Views to be used by this package
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter which package handles the
    | views, and which views are used specifically by each function.
    |
    */

    'views'     => [

        // Public views
        'public' => [

        ],

        // Admin views
        'admin'  => [
            'index'            => 'esensi/activity::activities.admin.index',
            'show'             => 'esensi/activity::activities.admin.show',

            // Modals
            'delete_confirm'   => 'esensi/activity::activities.admin.modals.delete-confirm',
            'truncate_confirm' => 'esensi/activity::activities.admin.modals.truncate-confirm',

            // Bulk actions
            'bulk'             => [
                'delete_confirm' => 'esensi/activity::activities.admin.modals.bulk-delete-confirm',
            ]
        ],
    ],

    'partials'  => [

        // Public partials
        'public' => [

        ],

        // Admin partials
        'admin'  => [

            'drawer'       => 'esensi/activity::activities.admin.partials.drawer',
            'bulk_actions' => 'esensi/core::core.admin.partials.bulk-actions',
        ],
    ],

    // Dropdown menus
    'dropdown'  => [
        'public' => null,
        'admin'  => 'esensi/activity::activities.admin.partials.dropdown',
    ],

    /*
    |--------------------------------------------------------------------------
    | Views to be used by this package
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter which routes are called when
    | the package's methods perform redirects.
    |
    */

    'redirects' => [

        // Admin redirects
        'admin' => [
            'truncated' => 'admin.activities.index',
            'deleted'   => 'admin.activities.index',

            // Bulk actions
            'bulk'      => [
                'deleted' => 'admin.activities.index',
            ],

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Thread cache TTL
    |--------------------------------------------------------------------------
    |
    | Time to live of the cache records used to control the tracking of threads
    | related to the same IP address. Specified in minutes.
    |
    */
    'ttl'       => [
        'tracking' => 60,
    ],

];
