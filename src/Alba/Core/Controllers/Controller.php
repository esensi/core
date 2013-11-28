<?php namespace Alba\Core\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
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
     * The module name
     * 
     * @var string
     */
    protected $module = 'core';

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

    /**
     * Assign a view to the layout's content
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @return void
     */
    protected function content($key, $data = [])
    {
        $package = Config::get('alba::' . str_singular($this->module) . '.views.packages.' . str_plural($this->module), 'alba::');
        $view = Config::get('alba::' . str_singular($this->module) . '.views.' . str_plural($this->module) . '.' . $key);
        $this->layout->content = View::make($package . $view, $data);
    }

    /**
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params to construct route
     * @return Redirect
     */
    protected function redirect($key, $params = [])
    {
        $name = Config::get('alba::' . str_singular($this->module) . '.redirects.' . str_plural($this->module) . '.' . $key, 'index');
        return Redirect::route($name, $params);
    }

    /**
     * Get a language line
     *
     * @param string $key to language config
     * @param array $replacements in language line
     * @return string
     */
    protected function language($key, $replacements = [])
    {
        return $this->resources[str_singular($this->module)]->language($key, $replacements);
    }

}