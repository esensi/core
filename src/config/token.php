<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Alba\User\Token module
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Alba\User\Token module. You can publish these to your project for
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
		'AlbaToken'					=> '\Alba\User\Models\Token',
		'AlbaTokensResource'		=> '\Alba\User\Resources\TokensResource',
		'AlbaTokensController'		=> '\Alba\User\Controllers\TokensController',
		'AlbaTokensAdminController'	=> '\Alba\User\Controllers\TokensAdminController',
		'AlbaTokensApiController'	=> '\Alba\User\Controllers\TokensApiController',
	],
	
	/*
	|--------------------------------------------------------------------------
	| TTL configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options set the Time-to-Live (TTL) for token
	| expiration. Values should be specified in hours.
	|
	*/

	'ttl' => [

		'default' => 24, // Default TTL used by all tokens
		'activation' => 24,
		'password_reset' => 24,
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
		
		'index' 			=> 'tokens.index',
	],

];