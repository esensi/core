<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'api'], function()
{
	$prefix = 'api.';
	Route::group(['before' => ['auth'] ], function() use ($prefix)
	{
		Route::any('user/logout', ['as' => $prefix.'user.logout', 'uses' => 'UserApi@logout']);
	});

	Route::post('user/login', ['as' => $prefix.'user.login', 'uses' => 'UserApi@login']);
	Route::resource('user', 'UserApi', ['except' => ['create','edit'] ]);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'admin/', 'before' => ['auth', 'admin'] ], function()
{
	$prefix = 'admin.';
		
	Route::get('user/create', ['as' => $prefix.'user.create', 'uses' => 'UserAdmin@create']);
	Route::get('user/{id}/edit', ['as' => $prefix.'user.edit', 'uses' => 'UserAdmin@edit'])->where(['id' => '[0-9]+']);
	Route::get('user', ['as' => $prefix.'user.index', 'uses' => 'UserAdmin@index']);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
*/

Route::get('change-password', ['as' => 'change-password', 'before' => ['auth'], 'uses' => 'UserController@changePassword']);
Route::get('login', ['as' => 'login', 'uses' => 'UserController@login']);
Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);
Route::get('profile', ['as' => 'profile', 'before' => ['auth'], 'uses' => 'UserController@profile']);
Route::get('register', ['as' => 'register', 'before' => ['guest'], 'uses' => 'UserController@register']);
Route::get('reset-password', ['as' => 'reset-password', 'before' => ['guest'], 'uses' => 'UserController@resetPassword']);