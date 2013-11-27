<?php namespace Alba\Core\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controllers\Controller as LaravelController;

/**
 * Core controller for base features of all module controllers
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class Controller extends LaravelController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';

    /**
     * The resources injected
     * 
     * @var array
     */
    protected $resources = [];

    /**
     * The APIs injected
     * 
     * @var array
     */
    protected $apis = [];

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}