<?php

/*
|--------------------------------------------------------------------------
| User Module Filters
|--------------------------------------------------------------------------
|
| The following filters provide route filtering for permissions, roles, and
| other User module specific routing features.
|
*/

Route::filter('permission', function($route, $request, $permission)
{
	if( !Entrust::can($permission) )
	{
		throw new \AlbaCoreResourceException(Lang::get('alba::user.errors.no_permission', ['permission' => $permission]));
	}
});