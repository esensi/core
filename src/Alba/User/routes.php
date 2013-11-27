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

Route::group(['prefix' => 'api', 'before' => 'error.api'], function()
{
	// User API Routes
	Route::group(['prefix' => 'user'], function(){
		Route::post('/login', [ 'as' => 'api.user.login', 'uses' => 'Alba\User\Controllers\UsersApiController@login' ]);
		Route::any('/logout', [ 'as' => 'api.user.logout', 'uses' => 'Alba\User\Controllers\UsersApiController@logout' ]);
		Route::delete('/{id}', [ 'as' => 'api.user.destroy', 'uses' => 'Alba\User\Controllers\UsersApiController@destroy' ]);
		Route::put('/{id}', [ 'as' => 'api.user.update', 'uses' => 'Alba\User\Controllers\UsersApiController@update' ]);
		Route::get('/{id}', [ 'as' => 'api.user.show', 'uses' => 'Alba\User\Controllers\UsersApiController@show' ]);
		Route::post('/', [ 'as' => 'api.user.store', 'uses' => 'Alba\User\Controllers\UsersApiController@store' ]);
		Route::get('/', [ 'as' => 'api.user.index', 'uses' => 'Alba\User\Controllers\UsersApiController@index' ]);
	})->where('id', '[0-9]+');

	// Role API Routes
	Route::group(['prefix' => 'role'], function(){
		Route::delete('/{id}', [ 'as' => 'api.role.destroy', 'uses' => 'Alba\User\Controllers\RolesApiController@destroy' ]);
		Route::put('/{id}', [ 'as' => 'api.role.update', 'uses' => 'Alba\User\Controllers\RolesApiController@update' ]);
		Route::get('/{id}', [ 'as' => 'api.role.show', 'uses' => 'Alba\User\Controllers\RolesApiController@show' ]);
		Route::post('/', [ 'as' => 'api.role.store', 'uses' => 'Alba\User\Controllers\RolesApiController@store' ]);
		Route::get('/', [ 'as' => 'api.role.index', 'uses' => 'Alba\User\Controllers\RolesApiController@index' ]);
	})->where('id', '[0-9]+');

	// Permission API Routes
	Route::group(['prefix' => 'permission'], function(){
		Route::delete('/{id}', [ 'as' => 'api.permission.destroy', 'uses' => 'Alba\User\Controllers\PermissionsApiController@destroy' ]);
		Route::put('/{id}', [ 'as' => 'api.permission.update', 'uses' => 'Alba\User\Controllers\PermissionsApiController@update' ]);
		Route::get('/{id}', [ 'as' => 'api.permission.show', 'uses' => 'Alba\User\Controllers\PermissionsApiController@show' ]);
		Route::post('/', [ 'as' => 'api.permission.store', 'uses' => 'Alba\User\Controllers\PermissionsApiController@store' ]);
		Route::get('/', [ 'as' => 'api.permission.index', 'uses' => 'Alba\User\Controllers\PermissionsApiController@index' ]);
	})->where('id', '[0-9]+');

	// Tokens API Routes
	Route::group(['prefix' => 'token'], function(){
		Route::delete('/{id}', [ 'as' => 'api.token.destroy', 'uses' => 'Alba\User\Controllers\TokensApiController@destroy' ]);
		Route::put('/{id}', [ 'as' => 'api.token.update', 'uses' => 'Alba\User\Controllers\TokensApiController@update' ]);
		Route::get('/{id}', [ 'as' => 'api.token.show', 'uses' => 'Alba\User\Controllers\TokensApiController@show' ]);
		Route::post('/', [ 'as' => 'api.token.store', 'uses' => 'Alba\User\Controllers\TokensApiController@store' ]);
		Route::get('/', [ 'as' => 'api.token.index', 'uses' => 'Alba\User\Controllers\TokensApiController@index' ]);
	})->where('id', '[0-9]+');
});

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

Route::group(['prefix' => 'admin', 'before' => 'error.default'], function()
{	
	// User Admin Routes
	Route::group(['prefix' => 'users'], function(){
		Route::get('/create', [ 'as' => 'admin.users.create', 'uses' => 'Alba\User\Controllers\UsersAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.users.search', 'uses' => 'Alba\User\Controllers\UsersAdminController@search' ]);
		Route::get('/{id}/block', [ 'as' => 'admin.users.block', 'uses' => 'Alba\User\Controllers\UsersController@block' ]);
		Route::get('/{id}/unblock', [ 'as' => 'admin.users.block', 'uses' => 'Alba\User\Controllers\UsersController@unblock' ]);
		Route::get('/{id}/deactivate', [ 'as' => 'admin.users.deactivate', 'uses' => 'Alba\User\Controllers\UsersController@deactivate' ]);
		Route::get('/{id}/reset-activation', [ 'as' => 'admin.users.reset-activation', 'uses' => 'Alba\User\Controllers\UsersController@resetActivation' ]);
		Route::get('/{id}/reset-password', [ 'as' => 'admin.users.reset-password', 'uses' => 'Alba\User\Controllers\UsersController@resetPassword' ]);
		Route::get('/{id}/edit', [ 'as' => 'admin.users.edit', 'uses' => 'Alba\User\Controllers\UsersAdminController@edit' ]);
		Route::get('/{id}', [ 'as' => 'admin.users.show', 'uses' => 'Alba\User\Controllers\UsersAdminController@show' ]);
		Route::get('/', [ 'as' => 'admin.users.index', 'uses' => 'Alba\User\Controllers\UsersAdminController@index' ]);
	})->where('id', '[0-9]+');

	// Roles Admin Routes
	Route::group(['prefix' => 'roles'], function(){
		Route::get('/create', [ 'as' => 'admin.roles.create', 'uses' => 'Alba\User\Controllers\RolesAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.roles.search', 'uses' => 'Alba\User\Controllers\RolesAdminController@search' ]);
		Route::get('/{id}/edit', [ 'as' => 'admin.roles.edit', 'uses' => 'Alba\User\Controllers\RolesAdminController@edit' ]);
		Route::get('/{id}', [ 'as' => 'admin.roles.show', 'uses' => 'Alba\User\Controllers\RolesAdminController@show' ]);
		Route::get('/', [ 'as' => 'admin.roles.index', 'uses' => 'Alba\User\Controllers\RolesAdminController@index' ]);
	})->where('id', '[0-9]+');

	// Permissions Admin Routes
	Route::group(['prefix' => 'permissions'], function(){
		Route::get('/create', [ 'as' => 'admin.permissions.create', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.permissions.search', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@search' ]);
		Route::get('/{id}/edit', [ 'as' => 'admin.permissions.edit', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@edit' ]);
		Route::get('/{id}', [ 'as' => 'admin.permissions.show', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@show' ]);
		Route::get('/', [ 'as' => 'admin.permissions.index', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@index' ]);
	})->where('id', '[0-9]+');

	// Tokens Admin Routes
	Route::group(['prefix' => 'tokens'], function(){
		Route::get('/create', [ 'as' => 'admin.tokens.create', 'uses' => 'Alba\User\Controllers\TokensAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.tokens.search', 'uses' => 'Alba\User\Controllers\TokensAdminController@search' ]);
		Route::get('/{id}/edit', [ 'as' => 'admin.tokens.edit', 'uses' => 'Alba\User\Controllers\TokensAdminController@edit' ]);
		Route::get('/{id}', [ 'as' => 'admin.tokens.show', 'uses' => 'Alba\User\Controllers\TokensAdminController@show' ]);
		Route::get('/', [ 'as' => 'admin.tokens.index', 'uses' => 'Alba\User\Controllers\TokensAdminController@index' ]);
	})->where('id', '[0-9]+');

});

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

Route::group(['before' => 'error.default'], function()
{
	Route::get('/account', [ 'as' => 'users.account', 'before' => 'auth', 'uses' => 'UsersController@account' ]);
	Route::match('get|post', '/activate/{token}', [ 'as' => 'users.activate', 'uses' => 'UsersController@activate' ]);
	Route::get('/activate-password/{token}', [ 'as' => 'users.activate-password', 'uses' => 'UsersController@activatePassword' ]);
	Route::post('/reset-activation', [ 'as' => 'users.reset-activation', 'uses' => 'UsersController@resetActivation' ]);
	Route::get('/new-activation', [ 'as' => 'users.new-activation', 'uses' => 'UsersController@newActivation' ]);
	Route::post('/set-password/{token}', [ 'as' => 'users.set-password', 'uses' => 'UsersController@setPassword' ]);
	Route::get('/set-password/{token}', [ 'as' => 'users.new-password', 'uses' => 'UsersController@newPassword' ]);
	Route::post('/forgot-password', [ 'as' => 'users.reset-password', 'uses' => 'UsersController@resetPassword' ]);
	Route::get('/forgot-password', [ 'as' => 'users.forgot-password', 'uses' => 'UsersController@forgotPassword' ]);
	Route::post('/login', [ 'as' => 'users.login', 'before' => 'guest', 'uses' => 'UsersController@login' ]);
	Route::get('/login', [ 'as' => 'users.signin', 'before' => 'guest', 'uses' => 'UsersController@signin' ]);
	Route::match('get|post|delete', '/logout', [ 'as' => 'users.logout', 'uses' => 'UsersController@logout' ]);
	Route::get('/registered', [ 'as' => 'users.registered', 'before' => 'guest', 'uses' => 'UsersController@registered' ]);
	Route::post('/register', [ 'as' => 'users.register', 'before' => 'guest', 'uses' => 'UsersController@register' ]);
	Route::get('/register', [ 'as' => 'users.signup', 'before' => 'guest', 'uses' => 'UsersController@signup' ]);
})->where('token', '[a-zA-Z0-9]+');