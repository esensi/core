<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Configuration values for Esensi\Build components package
	|--------------------------------------------------------------------------
	|
	| The following lines contain the default configuration values for the
	| Esensi\Build components package. You can publish these to your project for
	| modification using the following Artisan command:
	|
	| php artisan config:publish esensi/core
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| Application aliases
	|--------------------------------------------------------------------------
	|
	| The following configuration options allow the developer to map aliases to
	| controllers and models for easier customization of how Esensi handles
	| requests related to this components package. These aliases are loaded by
	| the service provider for this components package.
	|
	*/

	'aliases' => [
		'EsensiBuildCommand'			=> '\Esensi\Build\Commands\BuildCommand',
		'EsensiBuildWatchCommand'		=> '\Esensi\Build\Commands\BuildWatchCommand',
		'EsensiBuildCleanCommand'		=> '\Esensi\Build\Commands\BuildCleanCommand',
		'EsensiBuildStylesCommand'		=> '\Esensi\Build\Commands\BuildStylesCommand',
		'EsensiBuildScriptsCommand'		=> '\Esensi\Build\Commands\BuildScriptsCommand',
	],

	/*
	|--------------------------------------------------------------------------
	| Gulp Binary Location
	|--------------------------------------------------------------------------
	|
	| The following configuration option sets where the "gulp" command can be
	| found. For a local install it defaults to node_modules/.bin/gulp however
	| if installed globally you can set it with this option.
	|
	*/

	'binary' => base_path() . '/node_modules/.bin/gulp',
	
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
	| the same configurations in the Gulpfile.js.
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
	| in the Esensi\Core layout views by default. Styles (CSS) are added to the
	| header while scripts (JS) are added to the footer. You can still add any
	| asset you want manually in the header or footer from within the template.
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