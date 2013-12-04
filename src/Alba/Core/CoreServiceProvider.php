<?php namespace Alba\Core;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\HTML;

/**
 * Service provider for Alba\Core module
 *
 * @author diego <diego@emersonmedia.com>, daniel <daniel@bexarcreative.com>
 */
class CoreServiceProvider extends ServiceProvider {

    /**
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
    * Registers the resource dependencies
    *
    * @return void
    */
    public function register()
    {

    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindHTMLMacros();
    }

    /**
     * Bind the HTML macros
     *
     * @return void
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