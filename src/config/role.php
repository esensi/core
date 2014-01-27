<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Alba\User\Role module
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Alba\User\Role module. You can publish these to your project for
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
		'AlbaRole'					=> '\Alba\User\Models\Role',
		'AlbaRolesResource'			=> '\Alba\User\Resources\RolesResource',
		'AlbaRolesController'		=> '\Alba\User\Controllers\RolesController',
		'AlbaRolesAdminController'	=> '\Alba\User\Controllers\RolesAdminController',
		'AlbaRolesApiController'	=> '\Alba\User\Controllers\RolesApiController',
		'AlbaRolesTableSeeder'		=> '\Alba\User\Seeders\RolesTableSeeder',
	],
	
	/*
	|--------------------------------------------------------------------------
	| Role configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options are used by the roles seeder to
	| add default roles and assign different permissions to the roles. Roles are
	| then assigned to the users.
	|
	| Array of roles (key) and assigned permissions (value).
	| You can use the special permission "*" as a wildcard for all permissions.
	|
	| @example $roles = [ 'admin' => ['*'], 'user' => ['foo', 'bar'] ]
	|
	*/

    'defaults' => [

        'admin'          		=> ['*'],
        'user'					=> [],
    ],

	/*
	|--------------------------------------------------------------------------
	| TTL configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options set the Time-to-Live (TTL) for role
	| names caching. Values should be specified in minutes.
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
	| Views to be used by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which package handles the
	| views, and which views are used specifically by each function.
	|
	*/

	'views' => [
		
		'index' 			=> 'roles.index',
		'create'			=> 'roles.modals.form',
		'show'				=> 'roles.show',
		'edit'				=> 'roles.modals.form',

		// Modals
		'destroy_confirm'				=> 'roles.modals.destroy-confirm',
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

		'store'				=> 'admin.roles.index',
		'update'			=> 'admin.roles.index',
		'destroy'			=> 'admin.roles.index',
	],

];