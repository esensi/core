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
    | php artisan vendor:publish --provider="Esensi\Core\Providers\CoreServiceProvider"
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
    'aliases' => [
        'App\Console\Commands\Command'                  => Esensi\Core\Console\Commands\Command::class,
        'App\Exceptions\RepositoryException'            => Esensi\Core\Exceptions\RepositoryException::class,
        'App\Http\Controllers\AdminController'          => Esensi\Core\Http\Controllers\AdminController::class,
        'App\Http\Controllers\ApiController'            => Esensi\Core\Http\Controllers\ApiController::class,
        'App\Http\Controllers\PublicController'         => Esensi\Core\Http\Controllers\PublicController::class,
        'App\Http\Middleware\ApiAuthenticationVerifier' => Esensi\Core\Http\Middleware\ApiAuthenticationVerifier::class,
        'App\Http\Middleware\AuthenticatedRedirector'   => Esensi\Core\Http\Middleware\AuthenticatedRedirector::class,
        'App\Http\Middleware\AuthenticationVerifier'    => Esensi\Core\Http\Middleware\AuthenticationVerifier::class,
        'App\Http\Middleware\CsrfTokenVerifier'         => Esensi\Core\Http\Middleware\CsrfTokenVerifier::class,
        'App\Http\Middleware\RateLimiter'               => Esensi\Core\Http\Middleware\RateLimiter::class,
        'App\Http\Middleware\RobotsIndexer'             => Esensi\Core\Http\Middleware\RobotsIndexer::class,
        'App\Http\Requests\Request'                     => Esensi\Core\Http\Requestss\Request::class,
        'App\Jobs\Job'                                  => Esensi\Core\Jobs\Job::class,
        'App\Models\Collection'                         => Esensi\Core\Models\Collection::class,
        'App\Models\Model'                              => Esensi\Core\Models\Model::class,
        'App\Models\SoftModel'                          => Esensi\Core\Models\SoftModel::class,
        'App\Repositories\Repository'                   => Esensi\Core\Repositories\Repository::class,
        'App\Repositories\TrashableRepository'          => Esensi\Core\Repositories\TrashableRepository::class,
        'App\Seeders\Seeder'                            => Esensi\Core\Seeders\Seeder::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Component packages to load
    |--------------------------------------------------------------------------
    |
    | The following configuration options tell Esensi which component packages
    | are available. This can be useful for many things but is specifically used
    | by the template engine to determine how to render the administrative UI.
    |
    */

    'packages' => [
        'user',
        'activity',
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

    'prefixes' => [
        'admin'         => 'admin',
        'public'        => '',
        'api' => [
            'latest'    => 'api',
            'v1'        => 'api/v1',
        ]
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
    'ui' => [
        'admin'  => true,
        'public' => true,
    ],

    // APIs
    'api' => [
        'public' => true,
        'admin'  => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Views to be used by core package
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter which package handles the
    | views, and which views are used specifically by each function.
    |
    */

    'views' => [

        // Public views
        'public' => [

            'index'       => 'esensi/core::core.public.index',
            'modal'       => 'esensi/core::core.admin.modal',

            // Error views
            '404' => 'esensi/core::core.public.missing',
            '429' => 'esensi/core::core.public.whoops',
            '500' => 'esensi/core::core.public.whoops',
            '503' => 'esensi/core::core.public.maintenance',
        ],

        // Admin views
        'admin' => [

            'modal'   => 'esensi/core::core.admin.modal',
        ],
    ],

    'partials' => [

        // Public partials
        'public' => [

            'errors'  => 'esensi/core::core.admin.partials.errors',
            'footer'  => 'esensi/core::core.public.partials.footer',
            'header'  => 'esensi/core::core.public.partials.header',
        ],

        // Admin partials
        'admin' => [

            'account'      => 'esensi/core::core.admin.partials.dropdown',
            'drawer'       => 'esensi/core::core.admin.partials.drawer',
            'errors'       => 'esensi/core::core.admin.partials.errors',
            'footer'       => 'esensi/core::core.admin.partials.footer',
            'header'       => 'esensi/core::core.admin.partials.header',
            'logout'       => 'esensi/core::core.admin.partials.logout',
            'bulk_actions' => 'esensi/core::core.admin.partials.bulk-actions',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard link
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether the backend should
    | show or hide the dashboard menu item.
    |
    */

    'dashboard' => false,

    /*
    |--------------------------------------------------------------------------
    | Logout link
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether the backend should
    | show logout menu item (true) or simply redirect to the frontend (false).
    |
    */

    'logout' => false,

    /*
    |--------------------------------------------------------------------------
    | Attribution link
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether the backend should
    | show or hide the attribution menu item.
    |
    */

    'attribution' => [

        'enable' => true,
        'url'    => 'http://esen.si',
        'name'   => 'Powered by esensi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Meta data
    |--------------------------------------------------------------------------
    |
    | The following configuration options provide HTML meta data tags to the
    | header templates.
    |
    */

    'metadata' => [

        'keywords'    => 'emersonmedia esensi laravel boilerplate framework platform',
        'description' => 'Esensi is an awesome boilerplate application.',
        'author'      => 'esensi',
        'generator'   => gethostname(),
        'robots'      => env('APP_ENV') === 'production' ? 'Index, Follow' : 'NoIndex, NoFollow',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate limit settings
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether or not the rate
    | limiter should be enabled and how it should behave. The default behavior
    | is that it is enabled and set to reasonable levels to control potential
    | hacking threats. More than 60 requests to the same page without a cool
    | down period of at least 1 minute will generate a 10 minute timeout for
    | that IP address.
    |
    */

    'rates' => [

        // Should the limiter be enabled?
        'enabled' => true,

        // Should limits be based on unique routes?
        'routes'  => true,

        // Should route uniqueness be based on the route parameters?
        'parameters'  => true,

        // Request per period
        'limit'   => 60,

        // Period duration in minutes
        'period'  => 1,

        // Cache settings
        'cache' => [

            // Namespace for tags
            'tag'     => 'xrate',

            // Cache storage settings
            'driver'  => 'file',
            'table'   => 'cache',

            // Timeout (in minutes) an IP should be blocked
            'timeout' => 10,
        ],
    ],

];
