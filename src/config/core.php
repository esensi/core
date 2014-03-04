<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Alba\Core module
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Alba\Core module. You can publish these to your project for modification
	| using the following Artisan command:
	|
	| php artisan config:publish emersonmedia/alba
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| Application aliases
	|--------------------------------------------------------------------------
	|
	| The following configuration options allow the developer to map aliases to
	| controllers and models for easier customization of how Alba handles
	| requests related to the Core module. These aliases are loaded by the
	| service provider for this module.
	|
	*/

	'aliases' => [
		'AlbaCoreModel'				=> '\Alba\Core\Models\Model',
		'AlbaCoreResource'			=> '\Alba\Core\Resources\Resource',
		'AlbaCoreResourceException'	=> '\Alba\Core\Exceptions\ResourceException',
		'AlbaCoreResourceInterface'	=> '\Alba\Core\Contracts\ResourceInterface',
		'AlbaCoreController'		=> '\Alba\Core\Controllers\Controller',
		'AlbaCoreApiController'		=> '\Alba\Core\Controllers\ApiController',
		'AlbaCoreAdminController'	=> '\Alba\Core\Controllers\AdminController',
		'AlbaCoreSeeder'			=> '\Alba\Core\Seeders\Seeder',
		'AlbaCoreModuleProvider'	=> '\Alba\Core\Providers\ModuleServiceProvider',
	],

	/*
	|--------------------------------------------------------------------------
	| Modules to load
	|--------------------------------------------------------------------------
	|
	| The following configuration options tell Alba which modules are available.
	| This can be useful for many things but is specifically used by the template
	| engine to determine how to render the administrative interfaces.
	|
	*/

	'modules' => [
		'user',
	],

	/*
	|--------------------------------------------------------------------------
	| Configuration of module route prefixes
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter the route prefixes used for
	| the administrative backend, API, and module URLs.
	|
	*/

	'prefixes' => [
		'api' => [
			'latest'	=> 'api',
			'v1' 		=> 'api/v1',
		],
		'backend'		=> 'admin',
		'modules' => [
			'users'			=> 'users',
			'tokens'		=> 'tokens',
			'roles'			=> 'roles',
			'permissions'	=> 'permissions',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Routes to be included by all modules
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which routes are included,
	| effectively allowing the user to not use some or all of the default
	| routes available.
	|
	*/

	'routes' => [
		
		'api' 		=> false,
		'backend' 	=> true,
		'public'	=> true,
	],

	/*
	|--------------------------------------------------------------------------
	| Package to be used by core module
	|--------------------------------------------------------------------------
	|
	| The following configuration option alter which package namespace is used
	| for all of the views. Set to empty to use the application level views.
	|
	*/

	'package' => 'alba::',
	
	/*
	|--------------------------------------------------------------------------
	| Views to be used by core module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which package handles the
	| views, and which views are used specifically by each function.
	|
	*/

	'views' => [

		// Error pages
		'missing'			=> 'alba::core.missing',

		// Modals
		'modal'				=> 'core.modal',
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
	
];