<?php namespace Esensi\Core\Providers;

use Config;
use HTML;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

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
        $path = config_path($namespace);

        // Load views and language files
        $this->loadViewsFrom(__DIR__ . '/../views', $namespace);
        $this->loadTranslationsFrom(__DIR__ . '/../lang', $namespace);

        // Get the configs that need to be published
        $configs = [];
        $files = Finder::create()->files()->name('*.php')->in(__DIR__ . '/../config');
        foreach($files as $file)
        {
            $configs[$file->getRealPath()] = $path . '/' . basename($file->getRealPath());
        }

        // Publish the configs to the app namespace
        $this->publishes($configs, 'config');

        // Wrapped in a try catch because Finder squawks when there is no directory
        try{

            // Load the namespaced config files
            $files = Finder::create()->files()->name('*.php')->in($path);
            foreach($files as $file)
            {
                $key = $namespace . '::' . basename($file->getRealPath(), '.php');
                $this->app['config']->set($key, require $file->getRealPath());
            }

        } catch( InvalidArgumentException $e){}

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
