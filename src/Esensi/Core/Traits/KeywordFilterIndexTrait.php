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
     * @return void
     */
    protected function keywordFilterOptions()
    {
        // Get the options for filtering
        $model = $this->getRepository()->getModel();
        $orderOptions = $model->orderOptions;
        $sortOptions = $model->sortOptions;
        $maxOptions = $model->maxOptions;

        // Return the options needed by the view
        $data = compact('orderOptions', 'sortOptions', 'maxOptions');
        $inputs = Input::only('order', 'sort', 'max', 'keywords');

        // Convert arrays used in text inputs to comma-separated values
        if(is_array($inputs['keywords']))
        {
            $inputs['keywords'] = implode(', ', $inputs['keywords']);
        }

        return array_merge($inputs, $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        // Get the paginator using the parent API
        $paginator = parent::index();

        // Show collection as a paginated table
        $collection = $paginator->getCollection();
        $options = $this->keywordFilterOptions();
        $data = array_merge($options, compact('paginator', 'collection'));
        $this->content('index', $data);
    }

}
