<?php

namespace Esensi\Core\Traits;

use App\Support\Collection;

/**
 * Trait implementation of filterable repository interface.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\FilterableRepositoryInterface
 */
trait FilterableRepositoryTrait
{
    /**
     * The IDs to filter resource by.
     *
     * @var array
     */
    protected $ids = [];

    /**
     * The keywords to filter resource by.
     *
     * @var array
     */
    protected $keywords = [];

    /**
     * The filters.
     *
     * @var array
     */
    protected $filters = [
        'order' => 'id',
        'sort' => 'asc',
        'max' => 25,
        'trashed' => false,
    ];

    /**
     * Relationships to load on resource.
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * Scope closures to to filter resource by.
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * Search the resource using filters.
     *
     * @param object $query builder
     * @return Illuminate\Database\Query\Builder
     */
    public function filter($query)
    {
        // Bind filters in case it wasn't already done
        $this->bindFilters();

        // Set primary selection to the model's table
        $query->select([$query->getModel()->getTable().'.*']);

        // Filter with relationships
        $this->filterRelationships($query);

        // Filter for trashed resources
        $this->filterTrashed($query);

        // Filter for resources by IDs
        $this->filterIds($query);

        // Filter for resources by keyword
        $this->filterKeywords($query);

        // Filter for resources by query scope
        $this->filterScopes($query);

        $query->orderBy($this->order, $this->sort);

        return $query;
    }

    /**
     * Paginate the specified resource in storage.
     *
     * @param object $query builder
     * @return array
     */
    public function paginate($query)
    {
        return $query->paginate($this->max)
            ->appends(array_except($this->filters, ['scopes']));
    }

    /**
     * Filter query with relationships.
     *
     * @param object $query builder
     * @return void
     */
    public function filterRelationships($query)
    {
        if ( isset($this->relationships) )
        {
            // Parse relations1,relationsN into an array
            if( ! is_array($this->relationships) )
            {
                $relationships = Collection::parseMixed($this->relationships)->all();
                $this->relationships = $relationships;
            }

            // Add relationships to the query results
            $query->with($this->relationships);
        }
    }

    /**
     * Filter query for trashed resources.
     *
     * @param object $query builder
     * @return void
     */
    public function filterTrashed($query)
    {
        // Enable filter if model support it
        if ( method_exists($this->getModel(), 'forceDelete') && isset($this->trashed) )
        {
            // Only include the trashed results
            if( (string) $this->trashed == 'only' )
            {
                $query->onlyTrashed();
            }

            // Include both trashed and non-trashed results
            elseif( (boolean) $this->trashed === true )
            {
                $query->withTrashed();
            }

            // Exclude trashed results
            else
            {
                $query;
            }
        }
    }

    /**
     * Filter resources by IDs.
     *
     * @param object $query builder
     * @return void
     */
    public function filterIds($query)
    {
        // Get the model's key name
        $key = $this->getModel()->getKeyName();

        // Enable filter if model has IDs
        if( ! empty($this->ids) )
        {
            // Get an array of IDs
            $ids = Collection::parseMixed($this->ids, [',', '+', ' '])->all();

            // Query results for the IDs
            $query->whereIn($key, $ids);
        }
    }

    /**
     * Filter resources by keywords.
     *
     * @param object $query builder
     * @return void
     */
    public function filterKeywords($query)
    {
        // Get the model's searchable attributes
        $attributes = $this->getModel()->searchable;

        // Enable filter if model has searchable attributes
        if( ! empty($attributes) && ! empty($this->keywords) )
        {
            // Get an array of keywords
            $keywords = Collection::parseMixed($this->keywords, [','])->all();

            // Query results that have attributes containing the keywords
            $query->where(function( $query ) use ($keywords, $attributes)
            {
                foreach($attributes as $attribute)
                {
                    foreach($keywords as $keyword)
                    {
                        $query->orWhere($attribute, 'LIKE', '%' . $keyword . '%');
                    }
                }
            });
        }
    }

    /**
     * Filter resources by scope closures.
     *
     * @param object $query builder
     * @return void
     */
    public function filterScopes($query)
    {
        // Enable filter if we have scope closures
        if ( ! empty($this->scopes) )
        {
            foreach( $this->scopes as $scope => $args)
            {
                call_user_func_array([$query, $scope], $args);
            }
        }
    }

    /**
     * Add a scope filter.
     *
     * @param string $name of scope closure
     * @param mixed $args to pass to closure
     * @return void
     */
    public function addScope($name, $args)
    {
        // Make sure that scopes match the filters
        $scopes = isset($this->filters['scopes']) ? $this->filters['scopes'] : [];
        $this->scopes = array_merge($this->scopes ?: [], $scopes);

        // Convert mixed to array
        $args = Collection::parseMixed($args, [','])->all();
        $args = array_values($args);
        $arrs = array_filter($args, function($arg)
        {
            if( ! is_numeric($arg) && ! is_bool($arg) && empty($arg) )
            {
                return false;
            }
            return true;
        });

        // Only add the scope if the args are not empty
        if( ! empty($args) && $args == $arrs)
        {
            $this->scopes[ $name ] = $arrs;
            $this->filters['scopes'] = $this->scopes;
        }
    }

    /**
     * Add a boolean scope filter.
     *
     * @param string $name of scope closure
     * @param boolean $value to pass to closure
     * @return void
     */
    public function addBooleanScope($name, $value)
    {
        if( (is_bool($value) || is_numeric($value)) && ($value == 0 || $value == 1))
        {
            $this->mergeFilters([$name => $value]);
            $this->addScope('where' . studly_case($name), [(int) $value]);
        }
    }

    /**
     * Get the filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set the filters.
     *
     * @param array $filters
     * @return void
     */
    public function setFilters(array $filters = [])
    {
        $this->filters = $filters;
        $this->bindFilters();
    }

    /**
     * Merge the existing filters with new filters.
     *
     * @param array $filters
     * @return void
     */
    public function mergeFilters(array $filters = [])
    {
        $filters = array_filter($filters, function($value) {
            return ! is_null($value) && $value !== '';
        });
        $this->filters = array_merge($this->filters, $filters);
        $this->bindFilters();
    }


    /**
     * Bind the filters as properties.
     *
     * @return void
     */
    public function bindFilters()
    {
        // Assign filters as properties
        foreach( $this->filters as $key => $value )
        {
            $this->{$key} = $value;
        }

        // Make sure the sort order is lowercase
        $this->sort = strtolower( (string) $this->sort);

        // Make sure the max is a positive integer
        $this->max = max(0, (integer) $this->max);
    }

}
