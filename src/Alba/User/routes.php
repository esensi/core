<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the API part of the
| application. It is recommended to use Resource type routes.
|
*/

if ( Config::get('alba::user.routes.api', false) == true ):

Route::group([
	'prefix' => Config::get('alba::core.prefixes.api.latest', 'api'),
	'before' => ['error.api'],
], function()
{
	// User API Routes
	if ( Config::get('alba::user.routes.modules.users', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.users', 'users'),
		], function()
		{
			Route::get('suffixes', [ 'as' => 'api.user.suffixes', 'uses' => 'Alba\User\Controllers\UsersApiController@suffixes' ]);
			Route::get('titles', [ 'as' => 'api.user.titles', 'uses' => 'Alba\User\Controllers\UsersApiController@titles' ]);
			Route::post('login', [ 'as' => 'api.user.login', 'uses' => 'Alba\User\Controllers\UsersApiController@login' ]);
			Route::any('logout', [ 'as' => 'api.user.logout', 'uses' => 'Alba\User\Controllers\UsersApiController@logout' ]);
			Route::delete('{id}', [ 'as' => 'api.user.destroy', 'uses' => 'Alba\User\Controllers\UsersApiController@destroy' ]);
			Route::put('{id}', [ 'as' => 'api.user.update', 'uses' => 'Alba\User\Controllers\UsersApiController@update' ]);
			Route::get('{id}', [ 'as' => 'api.user.show', 'uses' => 'Alba\User\Controllers\UsersApiController@show' ]);
			Route::post('/', [ 'as' => 'api.user.store', 'uses' => 'Alba\User\Controllers\UsersApiController@store' ]);
			Route::get('/', [ 'as' => 'api.user.index', 'uses' => 'Alba\User\Controllers\UsersApiController@index' ]);
		})->where('id', '[0-9]+');
	endif;

	// Role API Routes
	if ( Config::get('alba::user.routes.modules.roles', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.roles', 'roles'),
		], function()
		{
			Route::delete('{id}', [ 'as' => 'api.role.destroy', 'uses' => 'Alba\User\Controllers\RolesApiController@destroy' ]);
			Route::put('{id}', [ 'as' => 'api.role.update', 'uses' => 'Alba\User\Controllers\RolesApiController@update' ]);
			Route::get('{id}', [ 'as' => 'api.role.show', 'uses' => 'Alba\User\Controllers\RolesApiController@show' ]);
			Route::post('/', [ 'as' => 'api.role.store', 'uses' => 'Alba\User\Controllers\RolesApiController@store' ]);
			Route::get('/', [ 'as' => 'api.role.index', 'uses' => 'Alba\User\Controllers\RolesApiController@index' ]);
		})->where('id', '[0-9]+');
	endif;

	// Permission API Routes
	if ( Config::get('alba::user.routes.modules.permissions', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.permissions', 'permissions'),
		], function()
		{
			Route::delete('{id}', [ 'as' => 'api.permission.destroy', 'uses' => 'Alba\User\Controllers\PermissionsApiController@destroy' ]);
			Route::put('{id}', [ 'as' => 'api.permission.update', 'uses' => 'Alba\User\Controllers\PermissionsApiController@update' ]);
			Route::get('{id}', [ 'as' => 'api.permission.show', 'uses' => 'Alba\User\Controllers\PermissionsApiController@show' ]);
			Route::post('/', [ 'as' => 'api.permission.store', 'uses' => 'Alba\User\Controllers\PermissionsApiController@store' ]);
			Route::get('/', [ 'as' => 'api.permission.index', 'uses' => 'Alba\User\Controllers\PermissionsApiController@index' ]);
		})->where('id', '[0-9]+');
	endif;

	// Tokens API Routes
	if ( Config::get('alba::user.routes.modules.tokens', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.tokens', 'tokens'),
		], function()
		{
			Route::delete('{id}', [ 'as' => 'api.token.destroy', 'uses' => 'Alba\User\Controllers\TokensApiController@destroy' ]);
			Route::put('{id}', [ 'as' => 'api.token.update', 'uses' => 'Alba\User\Controllers\TokensApiController@update' ]);
			Route::get('{id}', [ 'as' => 'api.token.show', 'uses' => 'Alba\User\Controllers\TokensApiController@show' ]);
			Route::post('/', [ 'as' => 'api.token.store', 'uses' => 'Alba\User\Controllers\TokensApiController@store' ]);
			Route::get('/', [ 'as' => 'api.token.index', 'uses' => 'Alba\User\Controllers\TokensApiController@index' ]);
		})->where('id', '[0-9]+');
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

if ( Config::get('alba::user.routes.backend', false) == true ):

Route::group([
	'prefix' => Config::get('alba::core.prefixes.backend', 'admin'), 
	'before' => ['error.default','auth', 'permission:module_users'],
], function()
{	
	// User Admin Routes
	if ( Config::get('alba::user.routes.modules.users', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.users', 'users'),
			'before' => ['permission:module_users'],
		], function()
		{
			// Create
			Route::post('create', [ 'as' => 'admin.users.store', 'uses' => 'Alba\User\Controllers\UsersAdminAdminController@store' ]);
			Route::get('create', [ 'as' => 'admin.users.create', 'uses' => 'Alba\User\Controllers\UsersAdminAdminController@create' ]);
			
			// Assign roles
			Route::post('{id}/roles', [ 'as' => 'admin.users.update.roles', 'before' => ['permission:module_roles'], 'uses' => 'Alba\User\Controllers\UsersAdminController@updateRoles' ]);
			Route::get('{id}/roles', [ 'as' => 'admin.users.edit.roles', 'before' => ['permission:module_roles'], 'uses' => 'Alba\User\Controllers\UsersAdminController@editRoles' ]);
			
			// Block
			Route::post('{id}/block', [ 'as' => 'admin.users.block', 'uses' => 'Alba\User\Controllers\UsersAdminController@block' ]);
			Route::get('{id}/block', [ 'as' => 'admin.users.block.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@blockConfirm' ]);
			
			// Unblock
			Route::post('{id}/unblock', [ 'as' => 'admin.users.unblock', 'uses' => 'Alba\User\Controllers\UsersAdminController@unblock' ]);
			Route::get('{id}/unblock', [ 'as' => 'admin.users.unblock.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@unblockConfirm' ]);
			
			// Activate
			Route::post('{id}/activate', [ 'as' => 'admin.users.activate', 'uses' => 'Alba\User\Controllers\UsersAdminController@activate' ]);
			Route::get('{id}/activate', [ 'as' => 'admin.users.activate.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@activateConfirm' ]);

			// Deactivate
			Route::post('{id}/deactivate', [ 'as' => 'admin.users.deactivate', 'uses' => 'Alba\User\Controllers\UsersAdminController@deactivate' ]);
			Route::get('{id}/deactivate', [ 'as' => 'admin.users.deactivate.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@deactivateConfirm' ]);

			// Reset Activaton / Password
			Route::post('{id}/reset-activation', [ 'as' => 'admin.users.reset-activation', 'uses' => 'Alba\User\Controllers\UsersAdminController@resetActivation' ]);
			Route::get('{id}/reset-activation', [ 'as' => 'admin.users.reset-activation.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@resetActivationConfirm' ]);
			Route::post('{id}/reset-password', [ 'as' => 'admin.users.reset-password', 'uses' => 'Alba\User\Controllers\UsersAdminController@resetPassword' ]);
			Route::get('{id}/reset-password', [ 'as' => 'admin.users.reset-password.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@resetPasswordConfirm' ]);
			
			// Edit
			Route::post('{id}/edit', [ 'as' => 'admin.users.update', 'uses' => 'Alba\User\Controllers\UsersAdminController@update' ]);
			Route::get('{id}/edit', [ 'as' => 'admin.users.edit', 'uses' => 'Alba\User\Controllers\UsersAdminController@edit' ]);
			
			// Delete / Restore
			Route::post('{id}/restore', [ 'as' => 'admin.users.restore', 'uses' => 'Alba\User\Controllers\UsersAdminController@restore' ]);
			Route::get('{id}/restore', [ 'as' => 'admin.users.restore.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@restoreConfirm' ]);
			Route::post('{id}/delete', [ 'as' => 'admin.users.destroy', 'uses' => 'Alba\User\Controllers\UsersAdminController@destroy' ]);
			Route::get('{id}/delete', [ 'as' => 'admin.users.destroy.confirm', 'uses' => 'Alba\User\Controllers\UsersAdminController@destroyConfirm' ]);
			
			// View
			Route::get('{id}', [ 'as' => 'admin.users.show', 'uses' => 'Alba\User\Controllers\UsersAdminController@show' ]);
			
			// Search / Browse
			Route::get('search', [ 'as' => 'admin.users.search', 'uses' => 'Alba\User\Controllers\UsersAdminController@search' ]);
			Route::get('trash', [ 'as' => 'admin.users.trash', 'uses' => 'Alba\User\Controllers\UsersAdminController@trash' ]);
			Route::get('/', [ 'as' => 'admin.users.index', 'uses' => 'Alba\User\Controllers\UsersAdminController@index' ]);
		})->where('id', '[0-9]+');
	endif;

	// Roles Admin Routes
	if ( Config::get('alba::user.routes.modules.roles', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.roles', 'roles'),
			'before' => ['permission:module_roles'],
		], function()
		{
			Route::get('create', [ 'as' => 'admin.roles.create', 'uses' => 'Alba\User\Controllers\RolesAdminController@create' ]);
			Route::get('search', [ 'as' => 'admin.roles.search', 'uses' => 'Alba\User\Controllers\RolesAdminController@search' ]);
			Route::get('{id}/edit', [ 'as' => 'admin.roles.edit', 'uses' => 'Alba\User\Controllers\RolesAdminController@edit' ]);
			Route::get('{id}', [ 'as' => 'admin.roles.show', 'uses' => 'Alba\User\Controllers\RolesAdminController@show' ]);
			Route::get('/', [ 'as' => 'admin.roles.index', 'uses' => 'Alba\User\Controllers\RolesAdminController@index' ]);
		})->where('id', '[0-9]+');
	endif;

	// Permissions Admin Routes
	if ( Config::get('alba::user.routes.modules.permissions', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.permissions', 'permissions'),
			'before' => ['permission:module_permissions'],
		], function()
		{
			Route::get('create', [ 'as' => 'admin.permissions.create', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@create' ]);
			Route::get('search', [ 'as' => 'admin.permissions.search', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@search' ]);
			Route::get('{id}/edit', [ 'as' => 'admin.permissions.edit', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@edit' ]);
			Route::get('{id}', [ 'as' => 'admin.permissions.show', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@show' ]);
			Route::get('/', [ 'as' => 'admin.permissions.index', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@index' ]);
		})->where('id', '[0-9]+');
	endif;

	// Tokens Admin Routes
	if ( Config::get('alba::user.routes.modules.tokens', false) == true ):
		Route::group([
			'prefix' => Config::get('alba::core.prefixes.modules.tokens', 'tokens'),
			'before' => ['permission:module_tokens'],
		], function()
		{
			Route::get('create', [ 'as' => 'admin.tokens.create', 'uses' => 'Alba\User\Controllers\TokensAdminController@create' ]);
			Route::get('search', [ 'as' => 'admin.tokens.search', 'uses' => 'Alba\User\Controllers\TokensAdminController@search' ]);
			Route::get('{id}/edit', [ 'as' => 'admin.tokens.edit', 'uses' => 'Alba\User\Controllers\TokensAdminController@edit' ]);
			Route::get('{id}', [ 'as' => 'admin.tokens.show', 'uses' => 'Alba\User\Controllers\TokensAdminController@show' ]);
			Route::get('/', [ 'as' => 'admin.tokens.index', 'uses' => 'Alba\User\Controllers\TokensAdminController@index' ]);
		})->where('id', '[0-9]+');
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
	Route::get('account', [ 'as' => 'users.account', 'before' => 'auth', 'uses' => 'UsersController@account' ]);
	Route::match('get|post', 'activate/{token}', [ 'as' => 'users.activate', 'uses' => 'UsersController@activate' ]);
	Route::get('activate-password/{token}', [ 'as' => 'users.activate-password', 'uses' => 'UsersController@activatePassword' ]);
	Route::post('reset-activation', [ 'as' => 'users.reset-activation', 'uses' => 'UsersController@resetActivation' ]);
	Route::get('new-activation', [ 'as' => 'users.new-activation', 'uses' => 'UsersController@newActivation' ]);
	Route::post('set-password/{token}', [ 'as' => 'users.set-password', 'uses' => 'UsersController@setPassword' ]);
	Route::get('set-password/{token}', [ 'as' => 'users.new-password', 'uses' => 'UsersController@newPassword' ]);
	Route::post('forgot-password', [ 'as' => 'users.reset-password', 'uses' => 'UsersController@resetPassword' ]);
	Route::get('forgot-password', [ 'as' => 'users.forgot-password', 'uses' => 'UsersController@forgotPassword' ]);
	Route::post('login', [ 'as' => 'users.login', 'before' => 'guest', 'uses' => 'UsersController@login' ]);
	Route::get('login', [ 'as' => 'users.signin', 'before' => 'guest', 'uses' => 'UsersController@signin' ]);
	Route::match('get|post|delete', 'logout', [ 'as' => 'users.logout', 'uses' => 'UsersController@logout' ]);
	Route::get('registered', [ 'as' => 'users.registered', 'before' => 'guest', 'uses' => 'UsersController@registered' ]);
	Route::post('register', [ 'as' => 'users.register', 'before' => 'guest', 'uses' => 'UsersController@register' ]);
	Route::get('register', [ 'as' => 'users.signup', 'before' => 'guest', 'uses' => 'UsersController@signup' ]);
})->where('token', '[a-zA-Z0-9]+');

endif;