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
     * @param object $query builder
     * @return void
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