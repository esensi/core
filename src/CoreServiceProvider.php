<?php namespace Esensi\Core;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use HTML;
use Config;

/**
 * Service provider for Esensi\Core components package
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class CoreServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $namespace = 'esensi/core';

        // Load views and language files
        $this->loadViewsFrom($namespace, __DIR__ . '/views');
        $this->loadTranslationsFrom($namespace, __DIR__ . '/lang');

        // Load config files
        Config::set($namespace . '::core', require __DIR__ . '/config/core.php' );
        Config::set($namespace . '::validation', require __DIR__ . '/config/validation.php' );

        // Setup core HTML macros
        // @todo: there's a better, more scalable way to do this
        $this->bindHTMLMacros();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bind the HTML macros
     *
     * @return void
     * @todo refactor this out somewhere else
     */
    protected function bindHTMLMacros()
    {
        /**
         * Bind paginationURL() for use with paginated links
         *
         * @example HTML::paginationUrl($paginator, $args, $page)
         * @param Paginator $paginator
         * @param array $args to append to paginator query string
         * @param integer $page number
         * @return string
         */
        HTML::macro('paginationUrl',
            function(Paginator $paginator, array $args = [], $page = 1)
            {
                $newPaginator = clone $paginator;

                // Get the queries appended to the Paginator
                $queries = [];
                $query_str = parse_url($newPaginator->getUrl($page), PHP_URL_QUERY);
                parse_str($query_str, $queries);

                // Reverse the sort order if already ordered
                if(isset($args['order']) && $queries['order'] == $args['order'])
                {
                    $args['sort'] = ($queries['sort'] == 'asc') ? 'desc' : 'asc';
                }

                // Return the full URL
                return $newPaginator->appends($args)->getUrl($page);
            }
        );
    }

}
