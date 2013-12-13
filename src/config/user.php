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
	| Views to be used by this module
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which package handles the
	| views, and which views are used specifically by each function.
	|
	*/

	'package' => 'alba::',

	'dropdown' => 'alba::users.dropdown',

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

		// Views used in emails
		'emails' => [
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
		'assign_roles'		=> 'admin.users.show',
	],

];