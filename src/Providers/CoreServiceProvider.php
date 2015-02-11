<?php namespace Esensi\Core\Providers;

use Esensi\Core\Traits\ConfigLoaderTrait;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
     * Make use of backported namespaced configs loader.
     *
     * @see Esensi\Core\Traits\ConfigLoaderTrait
     */
    use ConfigLoaderTrait;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $namespace = 'esensi/core';

        // Load configs, views and language files
        $this->loadConfigsFrom(__DIR__ . '/../config', $namespace);
        $this->loadViewsFrom(__DIR__ . '/../views', $namespace);
        $this->loadTranslationsFrom(__DIR__ . '/../lang', $namespace);

        // Setup core HTML macros
        $this->extendHtml();
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
     */
    protected function extendHtml()
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
        $this->app['html']->macro('paginationUrl',
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
