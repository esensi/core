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
	| Permission configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options are used by the permission seeder to
	| add permissions to the permissions table. Roles are then assigned these
	| permissions and the roles are assigned to the users.
	|
	*/

    'permissions' => [
    	
    	// Permission name 		=> Display name
        'module_users'          => 'Manage Users',
        'module_roles'          => 'Manage Roles',
        'module_permissions'    => 'Manage Permissions',
        'module_tokens'         => 'Manage Tokens',
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

    'roles' => [

        'admin'          		=> ['*'],
        'user'					=> [],
    ],

	/*
	|--------------------------------------------------------------------------
	| Token configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options set the Time-to-Live (TTL) for token
	| expiration. Values should be specified in hours.
	|
	*/

	'tokens' => [

		'ttl' => 24, // Default TTL used by all User tokens

		'activation' => [
			'ttl' => 24,
		],

		'password_reset' => [
			'ttl' => 24,
		],
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
		
		// Groups of routes
		'api' 		=> true,
		'backend' 	=> true,
		'public'	=> true,

		// Module routes within each group
		'modules' => [
			'users'			=> true,
			'tokens'		=> true,
			'roles'			=> true,
			'permissions'	=> true,
		],
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
			'users' 		=> 'alba::',
			'tokens' 		=> 'alba::',
			'roles' 		=> 'alba::',
			'permissions'	=> 'alba::',
		],

		// Views used by user module
		'users' => [
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

		// Views used by tokens module
		'tokens' => [
			'index' 			=> 'tokens.index',
			'show'				=> 'tokens.show',
		],

		// Views used by roles module
		'roles' => [
			'index' 			=> 'roles.index',
			'create'			=> 'roles.form',
			'show'				=> 'roles.show',
			'edit'				=> 'roles.form',
		],

		// Views used by permissions module
		'permissions' => [
			'index' 			=> 'permissions.index',
			'create'			=> 'permissions.form',
			'show'				=> 'permissions.show',
			'edit'				=> 'permissions.form',
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

		// Views used by user module
		'users' => [
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
		],
	],

];