<?php namespace Esensi\Core\Traits;

use \Esensi\Core\Traits\ModeledRepositoryTrait;

/**
 * Trait implementation of filterable repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\FilterableRepositoryInterface
 * @see \Esensi\Core\Traits\ModeledRepositoryTrait
 */
trait FilterableRepositoryTrait{

    /**
     * Make this repository use active record models
     *
     * @see \Esensi\Core\Traits\ModeledRepositoryTrait
     */
    use ModeledRepositoryTrait;

    /**
     * Keywords to filter resource by
     *
     * @var array
     */
    protected $keywords = [];

    /**
     * Filter options
     *
     * @var array
     */
    protected $options = [
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
        // Bind options in case it wasn't already done
        $this->bindOptions();

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
            ->appends($this->options);
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
     * @param array $args to pass to closure
     * @return void
     */
    public function addScope(string $name, array $args = [])
    {
        $this->scopes[ $name ] = $args;
        $this->options['scopes'] = $this->scopes;
    }

    /**
     * Set the filter options
     * 
     * @param array $options
     * @return void
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        $this->bindOptions();
    }

    /**
     * Merge the existing filter options with new filter options
     *
     * @param array $options
     * @return void
     */
    public function mergeOptions(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
        $this->bindOptions();
    }

    /**
     * Bind the options as properties
     *
     * @return void
     */
    protected function bindOptions()
    {
        // Assign options as properties
        foreach( $this->options as $key => $value )
        {
            $this->{$key} = $value;
        }

        // Make sure the sort order is lowercase
        $this->sort = strtolower( (string) $this->sort);

        // Make sure the max is a positive integer
        $this->max = max(0, (integer) $this->sort);
    }
}