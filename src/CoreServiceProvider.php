<?php namespace Esensi\Core;

use Esensi\Core\Providers\PackageServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Config;

/**
 * Service provider for Esensi\Core components package
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class CoreServiceProvider extends PackageServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Bind core class aliases
        $this->package('esensi/core', 'esensi/core', __DIR__);
        $this->addAliases('esensi/core', ['core']);

        // Add core filters and route patterns
        require __DIR__ . '/filters.php';
        require __DIR__ . '/routes.php';

        // Setup core HTML macros
        // @todo: there's a better, more scalable way to do this
        $this->bindHTMLMacros();
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
