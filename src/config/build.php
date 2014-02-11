<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Alba\Build module
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Alba\Build module. You can publish these to your project for modification
	| using the following Artisan command:
	|
	| php artisan config:publish emersonmedia/alba
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| Application aliases
	|--------------------------------------------------------------------------
	|
	| The following configuration options allow the developer to map aliases to
	| controllers and models for easier customization of how Alba handles
	| requests related to this module. These aliases are loaded by the
	| service provider for this module.
	|
	*/

	'aliases' => [
		'AlbaBuildCommand'			=> '\Alba\Build\Commands\BuildCommand',
		'AlbaBuildWatchCommand'		=> '\Alba\Build\Commands\BuildWatchCommand',
		'AlbaBuildCleanCommand'		=> '\Alba\Build\Commands\BuildCleanCommand',
		'AlbaBuildStylesCommand'	=> '\Alba\Build\Commands\BuildStylesCommand',
		'AlbaBuildScriptsCommand'	=> '\Alba\Build\Commands\BuildScriptsCommand',
	],

	/*
	|--------------------------------------------------------------------------
	| TTL configurations
	|--------------------------------------------------------------------------
	|
	| The following configuration options set the Time-to-Live (TTL) for
	| asset version caching. Values should be specified in minutes.
	|
	*/

	'ttl' => [
		'styles' => 60,
		'scripts' => 60,
	],

	/*
	|--------------------------------------------------------------------------
	| Production Environments
	|--------------------------------------------------------------------------
	|
	| The following configuration options set which environments should be
	| treated as "production" environments. These environments will combine
	| collections of assets into one file.
	|
	*/

	'environments' => [
		'production'
	],

	/*
	|--------------------------------------------------------------------------
	| Versioning
	|--------------------------------------------------------------------------
	|
	| The following configuration option sets whether or not non-production
	| environments should use the versioned assets (which are typically minified)
	| or if they should use the non-versioned ones.
	|
	*/

	'versions' => true,

	/*
	|--------------------------------------------------------------------------
	| Builds Directories
	|--------------------------------------------------------------------------
	|
	| The following configuration option sets where the builds should be stored.
	| The base path is relative to the public directory while the other paths
	| are relative to the base path. If you make changes here be sure to change
	| the same configurations in the Gruntfile.js.
	|
	*/

	'directories' => [
		'base' => 'builds',
		'scripts' => 'scripts',
		'styles' => 'styles',
	],
	
	/*
	|--------------------------------------------------------------------------
	| Collections to be used by build_styles() and build_scripts()
	|--------------------------------------------------------------------------
	|
	| The following configuration options alter which collections are included
	| in the Alba\Core layout views. Styles (CSS) are added to the header while
	| scripts (JS) are added to the footer.
	|
	*/

	'styles' => [
		'google-fonts',
		'font-awesome',
		'jquery-ui',
		'bootstrap-multiselect',
		'bootstrap-tagsinput',
		'application',
	],

	'scripts' => [
		'jquery',
		'jquery-ui',
		'bootstrap',
		'typeahead',
		'application',
	],
];