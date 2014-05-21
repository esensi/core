<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of filterable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\FilterableRepositoryInterface
 */
trait FilterableRepositoryTrait{

    /**
     * The keywords to filter resource by
     *
     * @var array
     */
    protected $keywords = [];

    /**
     * The filters
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
     * Relationships to load on resource
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * Scope closures to to filter resource by
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * Search the resource using filters
     *
     * @param object $query builder
     * @return void
     */
    public function filter($query)
    {
        // Bind filters in case it wasn't already done
        $this->bindFilters();

        // Set primary selection to the model's table
        $query->select([$model->getTable().'.*']);

        // Filter with relationships
        $this->filterRelationships($query);

        // Filter for trashed resources
        $this->filterTrashed($query);

        // Filter for resources by keyword
        $this->filterKeywords($query);

        // Filter for resources by query scope
        $this->filterScopes($query);

        $query->orderBy($this->order, $this->sort);
    }

    /**
     * Paginate the specified resource in storage.
     *
     * @param object $query builder
     * @return array
     */
    protected function paginate($query)
    {
        return $query->paginate($this->max)
            ->appends($this->filters);
    }

    /**
     * Filter query with relationships
     *
     * @param object $query builder
     * @return void
     */
    protected function filterRelationships($query)
    {
        if ( isset($this->relationships) )
        {
            $query->with($this->relationships);
        }
    }

    /**
     * Filter query for trashed resources
     *
     * @param object $query builder
     * @return void
     */
    protected function filterTrashed($query)
    {
        // Enable filter if model support it
        if ( $this->getModel()->isSoftDeleting() && isset($this->trashed) )
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
     * Filter resources by keywords
     *
     * @param object $query builder
     * @return void
     */
    protected function filterKeywords($query)
    {
        // Get the model's searchable attributes
        $attributes = $this->getModel()->searchableAttributes;

        // Enable filter if model has searchable attributes
        if( !empty($attributes) && ! empty($this->keywords) )
        {
            // Get an array of keywords
            $keywords = $this->keywords;
            if( is_string($keywords) )
            {
                $keywords = explode(',', trim(',', $keywords));
            }

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
     * Filter resources by scope closures
     *
     * @param object $query builder
     * @return void
     */
    protected function filterScopes($query)
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
     * Add a scope filter
     * 
     * @param string $name of scope closure
     * @param mixed $args to pass to closure
     * @return void
     */
    public function addScope($name, $args = [])
    {
        // Convert mixed to array
        $args = is_array($arr) ? $arr : explode(',', $args);
        $args = array_values($args);
        
        // Only add the scope if the args are not empty
        $test = implode('', $args);
        if( ! empty($test) )
        {
            $this->scopes[ $name ] = $args;
            $this->filters['scopes'] = $this->scopes;
        }
    }

    /**
     * Set the filter
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
     * Merge the existing filters with new filters
     *
     * @param array $filters
     * @return void
     */
    public function mergeFilters(array $filters = [])
    {
        $this->filters = array_merge($this->filters, $filters);
        $this->bindFilters();
    }

    /**
     * Bind the filters as properties
     *
     * @return void
     */
    protected function bindFilters()
    {
        // Assign filters as properties
        foreach( $this->filters as $key => $value )
        {
            $this->{$key} = $value;
        }

        // Make sure the sort order is lowercase
        $this->sort = strtolower( (string) $this->sort);

        // Make sure the max is a positive integer
        $this->max = max(0, (integer) $this->sort);
    }
}