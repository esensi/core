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

	'tokens' => [
		'activation' => [
			'ttl' => 24,
		],

		'password_reset' => [
			'ttl' => 24,
		],
	],

];