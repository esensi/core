<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Alba\User module
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Alba\User module. You can publish these to your project for modification
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
	| requests related to the User module. These aliases are loaded by the
	| service provider for this module.
	|
	*/

	'aliases' => [
		'AlbaUser'					=> '\Alba\User\Models\User',
		'AlbaName'					=> '\Alba\User\Models\Name',
		'AlbaUsersResource'			=> '\Alba\User\Resources\UsersResource',
		'AlbaUsersController'		=> '\Alba\User\Controllers\UsersController',
		'AlbaUsersAdminController'	=> '\Alba\User\Controllers\UsersAdminController',
		'AlbaUsersApiController'	=> '\Alba\User\Controllers\UsersApiController',
		'AlbaUsersTableSeeder'		=> '\Alba\User\Seeders\UsersTableSeeder',
		'AlbaUsersSeeder'			=> '\Alba\User\Seeders\UsersSeeder',
	],

	/*
	|--------------------------------------------------------------------------
	| TTL configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options set the Time-to-Live (TTL) for user
	| names caching. Values should be specified in minutes.
	|
	*/

	'ttl' => [
		'titles' => 10,
		'suffixes' => 10,
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

		'index' 			=> 'users.index',
		'signup'			=> 'users.signup',
		'registered'		=> 'users.reset-activation',
		'create'			=> 'users.form',
		'show'				=> 'users.show',
		'edit'				=> 'users.form',
		'signin'			=> 'users.signin',
		'forgot_password' 	=> 'users.forgot-password',
		'reset_password'	=> 'users.reset-password',
		'new_password'		=> 'users.new-password',
		'new_activation'	=> 'users.new-activation',
		'reset_activation'	=> 'users.reset-activation',
		'activate_password' => 'users.activate-password',
		'account' 			=> 'users.account',

		// Modals
		'activate_confirm'				=> 'users.modals.activate-confirm',
		'deactivate_confirm'			=> 'users.modals.deactivate-confirm',
		'block_confirm'					=> 'users.modals.block-confirm',
		'unblock_confirm'				=> 'users.modals.unblock-confirm',
		'restore_confirm'				=> 'users.modals.restore-confirm',
		'destroy_confirm'				=> 'users.modals.destroy-confirm',
		'reset_activation_confirm'		=> 'users.modals.reset-activation-confirm',
		'reset_password_confirm'		=> 'users.modals.reset-password-confirm',
		'search'						=> 'users.modals.search',
		'edit_roles'					=> 'users.modals.edit-roles',

		// Views used in emails
		'emails' => [
			'new_account' => [
				'emails.html.users.new-account',
				'emails.text.users.new-account',
			],
			'reset_activation' => [
				'emails.html.users.reset-activation',
				'emails.text.users.reset-activation',
			],
			'reset_password' => [
				'emails.html.users.reset-password',
				'emails.text.users.reset-password',
			],
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Dropdown view
	|--------------------------------------------------------------------------
	|
	| This special view is used in the administration to allow customization
	| of the module's dropdown menu.
	|
	*/

	'dropdown' => 'alba::users.dropdown',

	/*
	|--------------------------------------------------------------------------
	| Panel views
	|--------------------------------------------------------------------------
	|
	| These special views are used in the administration to allow customization
	| of the module's sub-views, effectively letting the developer add more
	| features to administrative views without having to completely overwrite
	| the existing view's logic. The order in which these are set define the
	| order in which they appear.
	|
	*/

	'panels' => [
	
		'alba::users.panels.user',
		'alba::users.panels.tokens',
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

		'register' 			=> 'users.registered',
		'store'				=> 'admin.users.show',
		'update'			=> 'admin.users.show',
		'update_password'	=> 'admin.users.show',
		'update_email'		=> 'admin.users.show',
		'login'				=> 'index',
		'logout'			=> 'users.signin',
		'set_password'		=> 'users.account',
		'activate' => [
			'guest'			=> 'users.account',
			'user'			=> 'admin.users.show',
		],
		'restore'			=> 'admin.users.trash',
		'destroy'			=> 'admin.users.index',
		'deactivate'		=> 'admin.users.show',
		'block'				=> 'admin.users.show',
		'unblock'			=> 'admin.users.show',
		'assign_roles' => [
			'admin'			=> 'admin.users.show',
			'user'			=> 'index',
		],
		'reset_password'	=> 'admin.users.show',
	],

];