<?php

/*
|--------------------------------------------------------------------------
| Route Patterns
|--------------------------------------------------------------------------
|
| Here you can define global patterns for route parameters.
|
*/

Route::pattern('user_token', '[a-zA-Z0-9]+');
Route::pattern('user_role', '^[a-z][a-z0-9\-_\.]+');
Route::pattern('user_permission', '^[a-z][a-z0-9\-_\.]+');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the API part of the
| application. It is recommended to use Resource type routes.
|
*/

if ( Config::get('alba::core.routes.api', false) == true ):

Route::group([
	'prefix' => Config::get('alba::core.prefixes.api.latest', 'api'),
	'before' => ['error.api'],
], function()
{
	// User API Routes
	if ( Config::get('alba::user.routes.api', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.users', 'users'),
		], function()
		{
			Route::get('roles', [ 'as' => 'api.user.roles', 'before' => ['permission:module_roles'], 'uses' => 'AlbaRolesApiController@names' ]);
			Route::get('suffixes', [ 'as' => 'api.user.suffixes', 'uses' => 'AlbaUsersApiController@suffixes' ]);
			Route::get('titles', [ 'as' => 'api.user.titles', 'uses' => 'AlbaUsersApiController@titles' ]);
			Route::post('login', [ 'as' => 'api.user.login', 'uses' => 'AlbaUsersApiController@login' ]);
			Route::any('logout', [ 'as' => 'api.user.logout', 'uses' => 'AlbaUsersApiController@logout' ]);
			Route::match(['POST', 'PUT'], '{id}/roles', [ 'as' => 'api.user.assign.roles', 'before' => ['permission:module_roles'], 'uses' => 'AlbaUsersApiController@assignRoles' ]);
			Route::delete('{id}', [ 'as' => 'api.user.destroy', 'uses' => 'AlbaUsersApiController@destroy' ]);
			Route::put('{id}', [ 'as' => 'api.user.update', 'uses' => 'AlbaUsersApiController@update' ]);
			Route::get('{id}', [ 'as' => 'api.user.show', 'uses' => 'AlbaUsersApiController@show' ]);
			Route::post('/', [ 'as' => 'api.user.store', 'uses' => 'AlbaUsersApiController@store' ]);
			Route::get('/', [ 'as' => 'api.user.index', 'uses' => 'AlbaUsersApiController@index' ]);
		});
	endif;

	// Role API Routes
	if ( Config::get('alba::role.routes.api', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.roles', 'roles'),
		], function()
		{
			Route::get('names', [ 'as' => 'api.role.names', 'uses' => 'AlbaRolesApiController@names' ]);
			Route::delete('{id}', [ 'as' => 'api.role.destroy', 'uses' => 'AlbaRolesApiController@destroy' ]);
			Route::put('{id}', [ 'as' => 'api.role.update', 'uses' => 'AlbaRolesApiController@update' ]);
			Route::get('{user_role}', [ 'as' => 'api.role.show.name', 'uses' => 'AlbaRolesApiController@showByName' ]);
			Route::get('{id}/permissions', [ 'as' => 'api.role.show.permissions', 'before' => ['permission:module_permissions'], 'uses' => 'AlbaRolesApiController@showPermissions' ]);
			Route::get('{id}/users', [ 'as' => 'api.role.show.users', 'before' => ['permission:module_users'], 'uses' => 'AlbaRolesApiController@showUsers' ]);
			Route::get('{id}', [ 'as' => 'api.role.show', 'uses' => 'AlbaRolesApiController@show' ]);
			Route::post('/', [ 'as' => 'api.role.store', 'uses' => 'AlbaRolesApiController@store' ]);
			Route::get('/', [ 'as' => 'api.role.index', 'uses' => 'AlbaRolesApiController@index' ]);
		});
	endif;

	// Permission API Routes
	if ( Config::get('alba::permission.routes.api', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.permissions', 'permissions'),
		], function()
		{
			Route::get('names', [ 'as' => 'api.permission.names', 'uses' => 'AlbaPermissionsApiController@names' ]);
			Route::put('{id}', [ 'as' => 'api.permission.update', 'uses' => 'AlbaPermissionsApiController@update' ]);
			Route::get('{user_permission}', [ 'as' => 'api.permission.show.name', 'uses' => 'AlbaPermissionsApiController@showByName' ]);
			Route::get('{id}/roles', [ 'as' => 'api.permission.show.roles', 'before' => ['permission:module_permissions'], 'uses' => 'AlbaPermissionsApiController@showRoles' ]);
			Route::get('{id}', [ 'as' => 'api.permission.show', 'uses' => 'AlbaPermissionsApiController@show' ]);
			Route::post('/', [ 'as' => 'api.permission.store', 'uses' => 'AlbaPermissionsApiController@store' ]);
			Route::get('/', [ 'as' => 'api.permission.index', 'uses' => 'AlbaPermissionsApiController@index' ]);
		});
	endif;

	// Tokens API Routes
	if ( Config::get('alba::token.routes.api', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.tokens', 'tokens'),
		], function()
		{
			Route::delete('{id}', [ 'as' => 'api.token.destroy', 'uses' => 'AlbaTokensApiController@destroy' ]);
			Route::delete('{user_token}', [ 'as' => 'api.token.destroy.token', 'uses' => 'AlbaTokensApiController@destroyByToken' ]);
			Route::put('{id}', [ 'as' => 'api.token.update', 'uses' => 'AlbaTokensApiController@update' ]);
			Route::get('{id}', [ 'as' => 'api.token.show', 'uses' => 'AlbaTokensApiController@show' ]);
			Route::get('{user_token}', [ 'as' => 'api.token.show.token', 'uses' => 'AlbaTokensApiController@showByToken' ]);
			Route::post('/', [ 'as' => 'api.token.store', 'uses' => 'AlbaTokensApiController@store' ]);
			Route::get('/', [ 'as' => 'api.token.index', 'uses' => 'AlbaTokensApiController@index' ]);
		});
	endif;
});

endif;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the administrative
| interface part of the application. It is recommended that the
| administration implement the API routes so as to be a light-weight UI
| for the existing functionality of the of the API.
|
*/

if ( Config::get('alba::core.routes.backend', false) == true ):

Route::group([
	'prefix' => Config::get('alba::core.prefixes.backend', 'admin'), 
	'before' => ['error.default','auth'],
], function()
{	
	// User Admin Routes
	if ( Config::get('alba::user.routes.backend', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.users', 'users'),
			'before' => ['permission:module_users'],
		], function()
		{
			// Create
			Route::post('create', [ 'as' => 'admin.users.store', 'uses' => 'AlbaUsersAdminController@store' ]);
			Route::get('create', [ 'as' => 'admin.users.create', 'uses' => 'AlbaUsersAdminController@create' ]);
			
			// Assign roles
			Route::post('{id}/roles', [ 'as' => 'admin.users.assign.roles', 'before' => ['permission:module_roles'], 'uses' => 'AlbaUsersAdminController@assignRoles' ]);
			Route::get('{id}/roles', [ 'as' => 'admin.users.edit.roles', 'before' => ['permission:module_roles'], 'uses' => 'AlbaUsersAdminController@editRoles' ]);
			
			// Block
			Route::post('{id}/block', [ 'as' => 'admin.users.block', 'uses' => 'AlbaUsersAdminController@block' ]);
			Route::get('{id}/block', [ 'as' => 'admin.users.block.confirm', 'uses' => 'AlbaUsersAdminController@blockConfirm' ]);
			
			// Unblock
			Route::post('{id}/unblock', [ 'as' => 'admin.users.unblock', 'uses' => 'AlbaUsersAdminController@unblock' ]);
			Route::get('{id}/unblock', [ 'as' => 'admin.users.unblock.confirm', 'uses' => 'AlbaUsersAdminController@unblockConfirm' ]);
			
			// Activate
			Route::post('{id}/activate', [ 'as' => 'admin.users.activate', 'uses' => 'AlbaUsersAdminController@activate' ]);
			Route::get('{id}/activate', [ 'as' => 'admin.users.activate.confirm', 'uses' => 'AlbaUsersAdminController@activateConfirm' ]);

			// Deactivate
			Route::post('{id}/deactivate', [ 'as' => 'admin.users.deactivate', 'uses' => 'AlbaUsersAdminController@deactivate' ]);
			Route::get('{id}/deactivate', [ 'as' => 'admin.users.deactivate.confirm', 'uses' => 'AlbaUsersAdminController@deactivateConfirm' ]);

			// Reset Activaton / Password
			Route::post('{id}/reset-activation', [ 'as' => 'admin.users.reset-activation', 'uses' => 'AlbaUsersAdminController@resetActivation' ]);
			Route::get('{id}/reset-activation', [ 'as' => 'admin.users.reset-activation.confirm', 'uses' => 'AlbaUsersAdminController@resetActivationConfirm' ]);
			Route::post('{id}/reset-password', [ 'as' => 'admin.users.reset-password', 'uses' => 'AlbaUsersAdminController@resetPassword' ]);
			Route::get('{id}/reset-password', [ 'as' => 'admin.users.reset-password.confirm', 'uses' => 'AlbaUsersAdminController@resetPasswordConfirm' ]);
			
			// Edit
			Route::post('{id}/edit', [ 'as' => 'admin.users.update', 'uses' => 'AlbaUsersAdminController@update' ]);
			Route::get('{id}/edit', [ 'as' => 'admin.users.edit', 'uses' => 'AlbaUsersAdminController@edit' ]);
			
			// Delete / Restore
			Route::post('{id}/restore', [ 'as' => 'admin.users.restore', 'uses' => 'AlbaUsersAdminController@restore' ]);
			Route::get('{id}/restore', [ 'as' => 'admin.users.restore.confirm', 'uses' => 'AlbaUsersAdminController@restoreConfirm' ]);
			Route::post('{id}/delete', [ 'as' => 'admin.users.destroy', 'uses' => 'AlbaUsersAdminController@destroy' ]);
			Route::get('{id}/delete', [ 'as' => 'admin.users.destroy.confirm', 'uses' => 'AlbaUsersAdminController@destroyConfirm' ]);
			
			// View
			Route::get('{id}', [ 'as' => 'admin.users.show', 'uses' => 'AlbaUsersAdminController@show' ]);
			
			// Search / Browse
			Route::get('search', [ 'as' => 'admin.users.search', 'uses' => 'AlbaUsersAdminController@search' ]);
			Route::get('trash', [ 'as' => 'admin.users.trash', 'uses' => 'AlbaUsersAdminController@trash' ]);
			Route::get('/', [ 'as' => 'admin.users.index', 'uses' => 'AlbaUsersAdminController@index' ]);

		});
	endif;

	// Roles Admin Routes
	if ( Config::get('alba::role.routes.backend', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.roles', 'roles'),
			'before' => ['permission:module_roles'],
		], function()
		{
			// Create
			Route::post('create', [ 'as' => 'admin.roles.store', 'uses' => 'AlbaRolesAdminController@store' ]);
			Route::get('create', [ 'as' => 'admin.roles.create', 'uses' => 'AlbaRolesAdminController@create' ]);
			
			// Delete
			Route::post('{id}/delete', [ 'as' => 'admin.roles.destroy', 'uses' => 'AlbaRolesAdminController@destroy' ]);
			Route::get('{id}/delete', [ 'as' => 'admin.roles.destroy.confirm', 'uses' => 'AlbaRolesAdminController@destroyConfirm' ]);

			// Edit
			Route::post('{id}/edit', [ 'as' => 'admin.roles.update', 'uses' => 'AlbaRolesAdminController@update' ]);
			Route::get('{id}/edit', [ 'as' => 'admin.roles.edit', 'uses' => 'AlbaRolesAdminController@edit' ]);

			// Show
			Route::get('{user_role}', [ 'as' => 'admin.roles.show.name', 'uses' => 'AlbaRolesAdminController@showByName' ]);
			Route::get('{id}', [ 'as' => 'admin.roles.show', 'uses' => 'AlbaRolesAdminController@show' ]);
			
			// Search / Browse
			Route::get('/', [ 'as' => 'admin.roles.index', 'uses' => 'AlbaRolesAdminController@index' ]);

		});
	endif;

	// Permissions Admin Routes
	if ( Config::get('alba::permission.routes.backend', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.permissions', 'permissions'),
			'before' => ['permission:module_permissions'],
		], function()
		{
			// Create
			Route::post('create', [ 'as' => 'admin.permissions.store', 'uses' => 'AlbaPermissionsAdminController@store' ]);
			Route::get('create', [ 'as' => 'admin.permissions.create', 'uses' => 'AlbaPermissionsAdminController@create' ]);
			
			// Edit
			Route::post('{id}/edit', [ 'as' => 'admin.permissions.update', 'uses' => 'AlbaPermissionsAdminController@update' ]);
			Route::get('{id}/edit', [ 'as' => 'admin.permissions.edit', 'uses' => 'AlbaPermissionsAdminController@edit' ]);

			// Show
			Route::get('{id}', [ 'as' => 'admin.permissions.show.name', 'uses' => 'AlbaPermissionsAdminController@showByName' ]);
			Route::get('{id}', [ 'as' => 'admin.permissions.show', 'uses' => 'AlbaPermissionsAdminController@show' ]);

			// Search / Browse
			Route::get('/', [ 'as' => 'admin.permissions.index', 'uses' => 'AlbaPermissionsAdminController@index' ]);
			
		});
	endif;

	// Tokens Admin Routes
	if ( Config::get('alba::token.routes.backend', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.tokens', 'tokens'),
			'before' => ['permission:module_tokens'],
		], function()
		{
			// Search / Browse
			Route::get('/', [ 'as' => 'admin.tokens.index', 'uses' => 'AlbaTokensAdminController@index' ]);

		});
	endif;
});

endif;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

if ( Config::get('alba::user.routes.public', false) == true ):

Route::group([
	'before' => 'error.default',
], function()
{
	Route::post('account/password', [ 'as' => 'users.update.password', 'before' => ['auth', 'error.default'], 'uses' => 'AlbaUsersController@updatePassword' ]);
	Route::post('account/email', [ 'as' => 'users.update.email', 'before' => ['auth', 'error.default'], 'uses' => 'AlbaUsersController@updateEmail' ]);
	Route::get('account', [ 'as' => 'users.account', 'before' => 'auth', 'uses' => 'AlbaUsersController@account' ]);
	Route::match(['GET', 'POST'], 'activate/{user_token}', [ 'as' => 'users.activate', 'uses' => 'AlbaUsersController@activate' ]);
	Route::get('activate-password/{user_token}', [ 'as' => 'users.activate-password', 'uses' => 'AlbaUsersController@activatePassword' ]);
	Route::post('reset-activation', [ 'as' => 'users.reset-activation', 'uses' => 'AlbaUsersController@resetActivation' ]);
	Route::get('new-activation', [ 'as' => 'users.new-activation', 'uses' => 'AlbaUsersController@newActivation' ]);
	Route::post('set-password/{user_token}', [ 'as' => 'users.set-password', 'uses' => 'AlbaUsersController@setPassword' ]);
	Route::get('set-password/{user_token}', [ 'as' => 'users.new-password', 'uses' => 'AlbaUsersController@newPassword' ]);
	Route::post('forgot-password', [ 'as' => 'users.reset-password', 'uses' => 'AlbaUsersController@resetPassword' ]);
	Route::get('forgot-password', [ 'as' => 'users.forgot-password', 'uses' => 'AlbaUsersController@forgotPassword' ]);
	Route::post('login', [ 'as' => 'users.login', 'before' => 'guest', 'uses' => 'AlbaUsersController@login' ]);
	Route::get('login', [ 'as' => 'users.signin', 'before' => 'guest', 'uses' => 'AlbaUsersController@signin' ]);
	Route::match(['GET', 'POST', 'DELETE'], 'logout', [ 'as' => 'users.logout', 'uses' => 'AlbaUsersController@logout' ]);
	Route::get('registered', [ 'as' => 'users.registered', 'before' => 'guest', 'uses' => 'AlbaUsersController@registered' ]);
	Route::post('register', [ 'as' => 'users.register', 'before' => 'guest', 'uses' => 'AlbaUsersController@register' ]);
	Route::get('register', [ 'as' => 'users.signup', 'before' => 'guest', 'uses' => 'AlbaUsersController@signup' ]);
});

endif;