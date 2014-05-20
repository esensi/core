<?php namespace Esensi\Core\Contracts;

/**
 * Filterable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface FilterableRepositoryInterface{

    /**
     * Search the resource using filters
     *
     * @return array
     */
    public function filter();

}