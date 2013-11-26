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

Route::group(['prefix' => 'api'], function(){
	
	// User API Routes
	Route::group(['prefix' => 'user'], function(){
		Route::post('/login', [ 'as' => 'api.user.login', 'uses' => 'Alba\User\Controllers\UsersApiController@login' ]);
		Route::any('/logout', [ 'as' => 'api.user.logout', 'uses' => 'Alba\User\Controllers\UsersApiController@logout' ]);
		Route::delete('/{id}', [ 'as' => 'api.user.destroy', 'uses' => 'Alba\User\Controllers\UsersApiController@destroy' ])->where('id', '[0-9]+');
		Route::put('/{id}', [ 'as' => 'api.user.update', 'uses' => 'Alba\User\Controllers\UsersApiController@update' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'api.user.show', 'uses' => 'Alba\User\Controllers\UsersApiController@show' ])->where('id', '[0-9]+');
		Route::post('/', [ 'as' => 'api.user.store', 'uses' => 'Alba\User\Controllers\UsersApiController@store' ]);
		Route::get('/', [ 'as' => 'api.user.index', 'uses' => 'Alba\User\Controllers\UsersApiController@index' ]);
	});

	// Role API Routes
	Route::group(['prefix' => 'role'], function(){
		Route::delete('/{id}', [ 'as' => 'api.role.destroy', 'uses' => 'Alba\User\Controllers\RolesApiController@destroy' ])->where('id', '[0-9]+');
		Route::put('/{id}', [ 'as' => 'api.role.update', 'uses' => 'Alba\User\Controllers\RolesApiController@update' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'api.role.show', 'uses' => 'Alba\User\Controllers\RolesApiController@show' ])->where('id', '[0-9]+');
		Route::post('/', [ 'as' => 'api.role.store', 'uses' => 'Alba\User\Controllers\RolesApiController@store' ]);
		Route::get('/', [ 'as' => 'api.role.index', 'uses' => 'Alba\User\Controllers\RolesApiController@index' ]);
	});

	// Permission API Routes
	Route::group(['prefix' => 'permission'], function(){
		Route::delete('/{id}', [ 'as' => 'api.permission.destroy', 'uses' => 'Alba\User\Controllers\PermissionsApiController@destroy' ])->where('id', '[0-9]+');
		Route::put('/{id}', [ 'as' => 'api.permission.update', 'uses' => 'Alba\User\Controllers\PermissionsApiController@update' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'api.permission.show', 'uses' => 'Alba\User\Controllers\PermissionsApiController@show' ])->where('id', '[0-9]+');
		Route::post('/', [ 'as' => 'api.permission.store', 'uses' => 'Alba\User\Controllers\PermissionsApiController@store' ]);
		Route::get('/', [ 'as' => 'api.permission.index', 'uses' => 'Alba\User\Controllers\PermissionsApiController@index' ]);
	});

	// Tokens API Routes
	Route::group(['prefix' => 'token'], function(){
		Route::delete('/{id}', [ 'as' => 'api.token.destroy', 'uses' => 'Alba\User\Controllers\TokensApiController@destroy' ])->where('id', '[0-9]+');
		Route::put('/{id}', [ 'as' => 'api.token.update', 'uses' => 'Alba\User\Controllers\TokensApiController@update' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'api.token.show', 'uses' => 'Alba\User\Controllers\TokensApiController@show' ])->where('id', '[0-9]+');
		Route::post('/', [ 'as' => 'api.token.store', 'uses' => 'Alba\User\Controllers\TokensApiController@store' ]);
		Route::get('/', [ 'as' => 'api.token.index', 'uses' => 'Alba\User\Controllers\TokensApiController@index' ]);
	});
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

Route::group(['prefix' => 'admin'], function(){
	
	// User Admin Routes
	Route::group(['prefix' => 'users'], function(){
		Route::get('/create', [ 'as' => 'admin.users.create', 'uses' => 'Alba\User\Controllers\UsersAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.users.search', 'uses' => 'Alba\User\Controllers\UsersAdminController@search' ]);
		Route::get('/{id}/change-password', [ 'as' => 'admin.users.change-password', 'uses' => 'Alba\User\Controllers\UsersAdminController@changePassword' ])->where('id', '[0-9]+');
		Route::get('/{id}/edit', [ 'as' => 'admin.users.edit', 'uses' => 'Alba\User\Controllers\UsersAdminController@edit' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'admin.users.show', 'uses' => 'Alba\User\Controllers\UsersAdminController@show' ])->where('id', '[0-9]+');
		Route::get('/', [ 'as' => 'admin.users.index', 'uses' => 'Alba\User\Controllers\UsersAdminController@index' ]);
	});

	// Roles Admin Routes
	Route::group(['prefix' => 'roles'], function(){
		Route::get('/create', [ 'as' => 'admin.roles.create', 'uses' => 'Alba\User\Controllers\RolesAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.roles.search', 'uses' => 'Alba\User\Controllers\RolesAdminController@search' ]);
		Route::get('/{id}/edit', [ 'as' => 'admin.roles.edit', 'uses' => 'Alba\User\Controllers\RolesAdminController@edit' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'admin.roles.show', 'uses' => 'Alba\User\Controllers\RolesAdminController@show' ])->where('id', '[0-9]+');
		Route::get('/', [ 'as' => 'admin.roles.index', 'uses' => 'Alba\User\Controllers\RolesAdminController@index' ]);
	});

	// Permissions Admin Routes
	Route::group(['prefix' => 'permissions'], function(){
		Route::get('/create', [ 'as' => 'admin.permissions.create', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.permissions.search', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@search' ]);
		Route::get('/{id}/edit', [ 'as' => 'admin.permissions.edit', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@edit' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'admin.permissions.show', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@show' ])->where('id', '[0-9]+');
		Route::get('/', [ 'as' => 'admin.permissions.index', 'uses' => 'Alba\User\Controllers\PermissionsAdminController@index' ]);
	});

	// Tokens Admin Routes
	Route::group(['prefix' => 'tokens'], function(){
		Route::get('/create', [ 'as' => 'admin.tokens.create', 'uses' => 'Alba\User\Controllers\TokensAdminController@create' ]);
		Route::get('/search', [ 'as' => 'admin.tokens.search', 'uses' => 'Alba\User\Controllers\TokensAdminController@search' ]);
		Route::get('/{id}/edit', [ 'as' => 'admin.tokens.edit', 'uses' => 'Alba\User\Controllers\TokensAdminController@edit' ])->where('id', '[0-9]+');
		Route::get('/{id}', [ 'as' => 'admin.tokens.show', 'uses' => 'Alba\User\Controllers\TokensAdminController@show' ])->where('id', '[0-9]+');
		Route::get('/', [ 'as' => 'admin.tokens.index', 'uses' => 'Alba\User\Controllers\TokensAdminController@index' ]);
	});

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

Route::post('/set-password', [ 'as' => 'users.save-password', 'uses' => 'Alba\User\Controllers\UsersController@savePassword' ]);
Route::get('/set-password/{token}', [ 'as' => 'users.set-password', 'uses' => 'Alba\User\Controllers\UsersController@setPassword' ])->where('token','[a-zA-Z0-9]+');
Route::post('/forgot-password', [ 'as' => 'users.reset-password', 'uses' => 'Alba\User\Controllers\UsersController@resetPassword' ]);
Route::get('/forgot-password', [ 'as' => 'users.forgot-password', 'uses' => 'Alba\User\Controllers\UsersController@forgotPassword' ]);
Route::post('/login', [ 'as' => 'users.login', 'uses' => 'Alba\User\Controllers\UsersController@login' ]);
Route::get('/login', [ 'as' => 'users.signin', 'uses' => 'Alba\User\Controllers\UsersController@signin' ]);
Route::any('/logout', [ 'as' => 'users.logout', 'uses' => 'Alba\User\Controllers\UsersController@logout' ]);
Route::post('/register', [ 'as' => 'users.register', 'uses' => 'Alba\User\Controllers\UsersController@register' ]);
Route::get('/register', [ 'as' => 'users.signup', 'uses' => 'Alba\User\Controllers\UsersController@signup' ]);