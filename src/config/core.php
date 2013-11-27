<?php

return [
		
	/*
	|--------------------------------------------------------------------------
	| Configuration of module route prefixes
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter the route prefixes used for
	| the administrative backend, API, and module URLs.
	|
	*/

	'prefixes' => [
		'api' => [
			'latest'	=> 'api',
			'v1' 		=> 'api/v1',
		],
		'backend'		=> 'admin',
		'modules' => [
			'users'			=> 'users',
			'tokens'		=> 'tokens',
			'roles'			=> 'roles',
			'permissions'	=> 'permissions',
		],
	],

];