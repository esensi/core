<?php namespace Esensi\Core\Controllers;

use \Illuminate\Support\Facades\Config;
use \Illuminate\Support\Facades\Lang;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\View;
use \Illuminate\Routing\Controller as LaravelController;

/**
 * Core controller for base features of all module controllers
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Resources\Resource
 * @see \Esensi\Core\Controllers\ApiController
 */
class Controller extends LaravelController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'esensi::core.default';

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
     * The controllers injected
     * 
     * @var array
     */
    protected $controllers = [];

    /**
     * The APIs injected
     * 
     * @var array
     */
    protected $apis = [];

    /**
     * Inject dependencies
     *
     * @param \Esensi\Core\Resources\Resource $resource;
     * @param \Esensi\Core\Controllers\ApiController $api;
     * @return void
     */
    public function __construct(\EsensiCoreResource $resource, \EsensiCoreApiController $api)
    {
        $this->setResource($resource);
        $this->setApi($api);
    }

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
     * Setup an array type of scope
     *
     * @param array $params for binding
     * @param string $key of Input
     * @param string $scope of query scope
     * @return void
     */
    protected function setupArrayTypeScope(&$params, $key, $scope)
    {
        if( $arr = Input::get($key, false) )
        {
            $arr = is_array($arr) ? $arr : explode(',', $arr);
            $arr = array_values($arr);
            $test = implode('', $arr);
            if(!empty($test))
            {
                $params[$key] = $arr;
                $params['scopes'][$scope] = [ $arr ];
            }
        }
    }

    /**
     * Assign a view to the layout's content
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @param string $name of content
     * @return View
     */
    protected function content($key, $data = [], $name = 'content')
    {
        $packageKey = str_singular($this->module) . '.package';
        $package = Config::get('esensi::' . $packageKey, Config::get($packageKey));
        $viewKey = str_singular($this->module) . '.views.' . $key;
        $view = Config::get('esensi::' . $viewKey, Config::get($viewKey));
        return $this->layout->$name = View::make($package . $view, $data);
    }

    /**
     * Generate a modal view
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @return View
     */
    protected function modal($key, $data = [], $name = 'modal-body')
    {
        $package = Config::get('esensi::core.package', 'esensi::');
        $view = Config::get('esensi::core.views.modal', 'core.modal');
        $this->layout = $package . $view;
        $this->setupLayout();
        return $this->content($key, $data, $name);
    }

    /**
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params to construct route
     * @param bool $back to previous route first
     * @return Redirect
     */
    protected function redirect($key, $params = [], $back = false)
    {
        // Short circuit to referrer URL
        $referer = Request::header('referer');
        if( $back && !empty($referer) )
        {
            return Redirect::back();
        }

        // Redirect to intended route
        $redirectKey = str_singular($this->module) . '.redirects.' . $key;
        $redirect = Config::get('esensi::'.$redirectKey, Config::get($redirectKey, 'index'));
        return Redirect::route($redirect, $params);
    }

    /**
     * Generate a redirect back to a previous URL
     *
     * @param string $key to route config
     * @param array $params to construct route
     * @return Redirect
     */
    protected function redirectBack($key, $params = [])
    {
        return $this->redirect($key, $params, true);
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
        return $this->getResource()->language($key, $replacements);
    }

    /**
     * Get the specified resource by name
     *
     * @param string $name of resource
     * @return \Esensi\Core\Resources\Resource
     * 
     */
    public function getResource($name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
        }

        return $this->resources[$name];
    }

    /**
     * Set the specified resource by name
     *
     * @param \Esensi\Core\Contracts\ResourceInterface $resource
     * @param string $name of resource
     * @return \Esensi\Core\Contracts\ResourceInterface
     * 
     */
    public function setResource(\EsensiCoreResourceInterface $resource, $name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
        }

        return $this->resources[$name] = $resource;
    }

    /**
     * Get the specified API by name
     *
     * @param string $name of API
     * @return \Esensi\Core\Controllers\Controller
     * 
     */
    public function getController($name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
        }

        return $this->controllers[$name];
    }

    /**
     * Set the specified API by name
     *
     * @param \Esensi\Core\Controllers\Controller $controller
     * @param string $name of controller
     * @return \Esensi\Core\Controllers\Controller
     * 
     */
    public function setController(\EsensiCoreController $controller, $name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
        }

        return $this->controllers[$name] = $controller;
    }

    /**
     * Get the specified API by name
     *
     * @param string $name of API
     * @return \Esensi\Core\Controllers\ApiController
     * 
     */
    public function getApi($name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
        }

        return $this->apis[$name];
    }

    /**
     * Set the specified API by name
     *
     * @param \Esensi\Core\Controllers\ApiController $api
     * @param string $name of api
     * @return \Esensi\Core\Controllers\ApiController
     * 
     */
    public function setApi(\AlbaCoreApiController $api, $name = null)
    {
        if(is_null($name))
        {
            $name = str_singular($this->module);
        }

        return $this->apis[$name] = $api;
    }

}