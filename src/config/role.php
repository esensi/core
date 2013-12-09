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
	| Names configurations
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
	| Views to be used by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which package handles the
	| views, and which views are used specifically by each function.
	|
	*/

	'views' => [
		
		// Package used by each module
		'packages' => [
			'roles' 		=> 'alba::',
		],

		// Views used by roles module
		'roles' => [
			'index' 			=> 'roles.index',
			'create'			=> 'roles.form',
			'show'				=> 'roles.show',
			'edit'				=> 'roles.form',

			// Modals
			'destroy_confirm'				=> 'roles.modals.destroy-confirm',
		],
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

		// Redirects used by role module
		'roles' => [
			'store'				=> 'admin.roles.show',
			'update'			=> 'admin.roles.show',
			'destroy'			=> 'admin.roles.index',
		],
	],

];