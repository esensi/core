<?php namespace Esensi\Core\Extensions;

use Illuminate\Pagination\AbstractPaginator;

/**
 * Extension bindings for HTML macros.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class HtmlMacros {

    /**
     * Bind paginationUrl() for use with paginated links
     *
     * @example HTML::paginationUrl($paginator, $args, $page)
     * @param AbstractPaginator $paginator
     * @param array $args to append to paginator query string
     * @param integer $page number
     * @return string
     */
    public static function paginationUrl(AbstractPaginator $paginator, array $args = [], $page = 1)
    {
        $newPaginator = clone $paginator;

        // Get the queries appended to the Paginator
        $queries = [];
        $query_str = parse_url($newPaginator->url($page), PHP_URL_QUERY);
        parse_str($query_str, $queries);

        // Reverse the sort order if already ordered
        if(isset($args['order']) && isset($queries['order']) && $queries['order'] == $args['order'])
        {
            $args['sort'] = ($queries['sort'] == 'asc') ? 'desc' : 'asc';
        }

        // Return the full URL
        return $newPaginator->appends($args)->url($page);
    }

}
