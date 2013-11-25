<?php namespace Alba\Core\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controllers\Controller as LaravelController;

class Controller extends LaravelController {

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