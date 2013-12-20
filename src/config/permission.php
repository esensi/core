<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Alba\User\Permission module
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Alba\User\Permission module. You can publish these to your project for
	| modification using the following Artisan command:
	|
	| php artisan config:publish emersonmedia/alba
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| Class aliases
	|--------------------------------------------------------------------------
	|
	| The following configuration options allow the developer to map aliases to
	| controllers and models for easier customization of how Alba handles
	| requests related to the this module. These aliases are loaded by the
	| service provider for this module.
	|
	*/

	'aliases' => [
		'AlbaPermission'					=> '\Alba\User\Models\Permission',
		'AlbaPermissionsResource'			=> '\Alba\User\Controllers\PermissionsResource',
		'AlbaPermissionsController'			=> '\Alba\User\Controllers\PermissionsController',
		'AlbaPermissionsAdminController'	=> '\Alba\User\Controllers\PermissionsAdminController',
		'AlbaPermissionsApiController'		=> '\Alba\User\Controllers\PermissionsApiController',
		'AlbaPermissionsTableSeeder'		=> '\Alba\User\Seeders\PermissionsTableSeeder',
	],

	/*
	|--------------------------------------------------------------------------
	| Permission configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options are used by the permission seeder to
	| add permissions to the permissions table. Roles are then assigned these
	| permissions and the roles are assigned to the users.
	|
	*/

    'defaults' => [
    	
    	// Permission name 		=> Display name
        'module_users'          => 'Manage Users',
        'module_roles'          => 'Manage Roles',
        'module_permissions'    => 'Manage Permissions',
        'module_tokens'         => 'Manage Tokens',
    ],

	/*
	|--------------------------------------------------------------------------
	| TTL configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options set the Time-to-Live (TTL) for
	| permission names caching. Values should be specified in minutes.
	|
	*/

	'ttl' => [
		'names' => 10,
	],

	/*
	|--------------------------------------------------------------------------
	| Routes to be included by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which routes are included,
	| effectively allowing the user to not use some or all of the default
	| routes available.
	|
	*/

	'routes' => [
		
		'api' 		=> true,
		'backend' 	=> true,
		'public'	=> true,
	],
	
	/*
	|--------------------------------------------------------------------------
	| Views to be used by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which package handles the
	| views, and which views are used specifically by each function.
	|
	*/

	'package' => 'alba::',

	'views' => [
		
		'index' 			=> 'permissions.index',
		'create'			=> 'permissions.modals.form',
		'show'				=> 'permissions.show',
		'edit'				=> 'permissions.modals.form',
	],

	/*
	|--------------------------------------------------------------------------
	| Redirects to be used by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which routes are called when
	| the module's methods perform redirects.
	|
	*/

	'redirects' => [
	
		'store'				=> 'admin.permissions.index',
		'update'			=> 'admin.permissions.index',
	],

];