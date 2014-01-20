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
		
		'api' 		=> true,
		'backend' 	=> true,
		'public'	=> true,
	],

	/*
	|--------------------------------------------------------------------------
	| Views to be used by core module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which package handles the
	| views, and which views are used specifically by each function.
	|
	*/

	'package' => 'alba::',

	'views' => [

		// Error pages
		'missing'			=> 'alba::core.missing',

		// Modals
		'modal'				=> 'core.modal',
	],

	'javascripts' => [
		'jqueryui', 'bootstrap', 'typeahead', 'application',
	],

	'stylesheets' => [
		'jqueryui', 'bootstrap', 'fontawesome', 'application',
	],
];