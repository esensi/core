<?php

namespace Esensi\Core\Contracts;

/**
 * Filterable repository interface
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface FilterableRepositoryInterface
{
    /**
     * Search the resource using filters
     *
     * @param object $query builder
     * @return Illuminate\Database\Query\Builder
     */
    public function filter($query);

    /**
     * Paginate the specified resource in storage.
     *
     * @param object $query builder
     * @return array
     */
    public function paginate($query);

    /**
     * Filter query with relationships
     *
     * @param object $query builder
     * @return void
     */
    function filterRelationships($query);

    /**
     * Filter query for trashed resources
     *
     * @param object $query builder
     * @return void
     */
    function filterTrashed($query);

    /**
     * Filter resources by IDs.
     *
     * @param object $query builder
     * @return void
     */
    function filterIds($query);

    /**
     * Filter resources by keywords
     *
     * @param object $query builder
     * @return void
     */
    function filterKeywords($query);

    /**
     * Filter resources by scope closures
     *
     * @param object $query builder
     * @return void
     */
    function filterScopes($query);

    /**
     * Add a scope filter
     *
     * @param string $name of scope closure
     * @param mixed $args to pass to closure
     * @return void
     */
    public function addScope($name, $args);

    /**
     * Add a boolean scope filter
     *
     * @param string $name of scope closure
     * @param boolean $value to pass to closure
     * @return void
     */
    public function addBooleanScope($name, $value);

    /**
     * Get the filters
     *
     * @return array
     */
    public function getFilters();

    /**
     * Set the filters
     *
     * @param array $filters
     * @return void
     */
    public function setFilters(array $filters = []);

    /**
     * Merge the existing filters with new filters
     *
     * @param array $filters
     * @return void
     */
    public function mergeFilters(array $filters = []);

    /**
     * Bind the filters as properties
     *
     * @return void
     */
    function bindFilters();

}
