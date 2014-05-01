<?php

/*
|--------------------------------------------------------------------------
| Route Patterns
|--------------------------------------------------------------------------
|
| Here you can define global patterns for route parameters.
|
*/

Route::pattern('id', '[0-9]+');
Route::pattern('slug', '[a-z][a-z0-9\-\_]+');
Route::pattern('token', '[a-zA-Z0-9]+');