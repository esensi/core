<?php namespace Esensi\Core\Traits;

use \Illuminate\Support\Facades\App;

/**
 * Traits for helping with package configurations
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\PackagedInterface
 */
trait PackagedTrait{

    /**
     * The package name
     * 
     * @var string
     */
    protected $package = 'core';

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = '';

    /**
     * The UI name
     * 
     * @var string
     */
    protected $ui = 'public';

    /**
     * Setup the layout used by the controller.
     * This is part of Laravel's internal controller
     * layout handlers. It should stay here.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = App::make('view')->make($this->layout);
        }
    }

    /**
     * Generate a subview for the layout
     *
     * @param string $key to view config
     * @param array $data (optional) to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    protected function content(string $key, array $data = [], string $name = null)
    {
        // Assign a default content name
        $name = is_null($name) ? 'content' : $name;

        // Get the confg line for the view
        $line = $this->config('views.' . $this->ui . '.' . $key);
        
        // Nest the view into the layout
        $view = App::make('view')->make($package . $view, $data);
        $this->layout->$name = $response;
        
        return $view;
    }

    /**
     * Generate a modal view
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    protected function modal(string $key, array $data = [], string $name = null)
    {
        // Change default layout to modal layout
        $layout = $this->namespacing() . 'core.views.' . $this->ui . '.modal';
        $this->layout = $this->layout;
        $this->setupLayout();
        
        // Just return the response from the proxy call
        return $this->content($key, $data, $name);
    }

    /**
     * Get a configuration line
     *
     * @param string $key to config line
     * @return mixed
     */
    protected function config(string $key)
    {
        $namespace = 'esensi::';
        $line = str_singular($this->package) . '.' .$key;
        $loader = App::make('config');

        // Use local namespaced package
        if( $loader->has($namespace . $line) )
        {
            return $loader->get($namespace . $line);
        }

        // Use global namespaced package
        else
        {
            return $loader->get($line);
        }
    }

    /**
     * Get the package namespace
     *
     * @return string
     */
    protected function namespacing()
    {
        $core = $this->config('esensi::core.namespace');
        $package = str_singular($this->package) . '.namespace';
        return $this->config($core . $package);
    }

    /**
     * Get a language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function language(string $key, array $replacements = [])
    {
        $namespace = 'esensi::';
        $line = str_singular($this->package) . '.' .$key;
        $loader = App::make('translator');

        // Use local namespaced package
        if( $loader->has($namespace . $line) )
        {
            return $loader->get($namespace . $line, $replacements);
        }

        // Use global namespaced package
        else
        {
            return $loader->get($line, $replacements);
        }
    }

    /**
     * Get an error language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function error(string $key, array $replacements = [])
    {
        return $this->language('errors.' . $key, $replacements);
    }

    /**
     * Get a message language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function message(string $key, array $replacements = [])
    {
        return $this->language('messages.' . $key, $replacements);
    }

    /**
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    protected function redirect(string $key, array $params = [])
    {
        // Redirect to intended route
        $route = $this->config('redirects.' . $this->ui . '.' . $key);
        return App::make('redirect')->route($route, $params);
    }

    /**
     * Generate a redirect back
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    protected function back(string $key, array $params = [])
    {
        // Short circuit to referrer URL or follow redirect
        $referer = App::make('request')->header('referer');
        $redirect = !empty($referer) ? App::make('redirect')->back() : $this->redirect($key, $params);
    }

}