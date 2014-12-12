<?php namespace Esensi\Core\Traits;

use \Illuminate\Support\Facades\Input;

/**
 * Index method with filter options passed to the view.
 *
 * @author Daniel LaBarge <dalabarge@emersonmedia.com>
 * @see \Esensi\Core\Traits\ResourceControllerTrait
 */
trait KeywordFilterIndexTrait {

    /**
     * Get the options needed by keyword filter drawer.
     *
     * @return array
     */
    protected function keywordFilterOptions()
    {
        // Get the options for filtering
        $model = $this->getRepository()->getModel();
        $orderOptions = $model->orderOptions;
        $sortOptions = $model->sortOptions;
        $maxOptions = $model->maxOptions;

        // Combine the options needed by the view
        $options = compact('orderOptions', 'sortOptions', 'maxOptions');

        // Apply default filter fallbacks for missing inputs
        $inputs  = Input::all();
        $filters = $this->getRepository()->getFilters();
        $keys    = ['order', 'sort', 'max', 'keywords'];
        foreach($keys as $key)
        {
            $options[ $key ] = array_get($inputs, $key, array_get($filters, $key, null));
        }

        // Convert arrays used in text inputs to comma-separated values
        if(is_array($options['keywords']))
        {
            $options['keywords'] = implode(', ', $options['keywords']);
        }

        return $options;
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        // Get the paginator using the parent API
        $paginator = $this->api()->index();

        // Show collection as a paginated table
        $collection = $paginator->getCollection();
        $options = $this->keywordFilterOptions();
        $data = array_merge($options, compact('paginator', 'collection'));
        $this->content('index', $data);
    }

}
