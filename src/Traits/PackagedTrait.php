<?php

namespace Esensi\Core\Traits;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;

/**
 * Traits for helping with package configurations
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @author Diego Caprioli <diego@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @see Esensi\Core\Contracts\PackagedInterface
 */
trait PackagedTrait
{
    /**
     * The namespace that should be used by the package.
     *
     * @var string
     */
    protected $namespacing = 'esensi/core';

    /**
     * The package name.
     *
     * @var string
     */
    protected $package = 'core';

    /**
     * The UI name.
     *
     * @var string
     */
    protected $ui = 'public';

    /**
     * Get the package name.
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set the package name.
     *
     * @param string $package
     * @return void
     */
    public function setPackage( $package )
    {
        $this->package = $package;
    }

    /**
     * Get the UI name.
     *
     * @return string
     */
    public function getUI()
    {
        return $this->ui;
    }

    /**
     * Set the UI name.
     *
     * @param string $ui
     * @return void
     */
    public function setUI( $ui )
    {
        $this->ui = $ui;
    }

    /**
     * Get the namespace name.
     *
     * @return string
     */
    public function getNamespacing()
    {
        return $this->namespacing;
    }

    /**
     * Set the namespace name.
     *
     * @param string $namespace
     * @return void
     */
    public function setNamespacing( $namespace )
    {
        $this->namespacing = $namespace;
    }

    /**
     * Get the package namespace.
     *
     * @return string
     */
    public function namespacing()
    {
        return $this->namespacing ? trim($this->namespacing, '::') . '::' : '';
    }

    /**
     * Resolve a namespaced line.
     *
     * @param mixed $resolver
     * @param string $key to config line
     * @param mixed $arguments (optional)
     * @return string
     */
    protected function resolve($resolver, $key, $arguments = null)
    {
        // Get the package namespace
        $namespace = stripos($key, '::') === false ? $this->namespacing() : '';

        // Get the package line
        $line = str_singular($this->package) . '.' .$key;

        // Use package namespace line
        // @example: esensi/<package>::<package>.foo
        if( $resolver->has($namespace . $line) )
        {
            return $resolver->get($namespace . $line, $arguments);
        }

        // Use global namespace line
        // @example: <package>.foo
        if( $resolver->has($line) )
        {
            return $resolver->get($line, $arguments);
        }

        // Use core namespace line
        // @example: esensi/core:core.foo
        return $resolver->get('esensi/core::core.' . $key, $arguments);
    }

    /**
     * Generate a subview for the layout.
     *
     * @param string $key to view config
     * @param array $data (optional) to be passed to view
     * @param string $name (optional) of content
     * @return Illuminate\View\View
     * @throws InvalidArgumentException
     */
    public function content($key, array $data = [], $name = null)
    {
        // Assign a default content name
        $name = is_null($name) ? 'content' : $name;

        // Get the confg line for the view
        $config = 'views.' . $this->ui . '.' . $key;
        $line = $this->config($config);
        if( is_null($line) )
        {
            throw new InvalidArgumentException('View config line ['.$config.'] not found.');
        }

        // Nest the view into the layout
        $view = App::make('view')->make($line, $data);
        $this->layout->$name = $view;
        return $this->layout;
    }

    /**
     * Generate a modal view.
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @param string $name (optional) of content
     * @return Illuminate\View\View
     */
    public function modal($key, array $data = [], $name = null)
    {
        // Check to see if the config line is defined
        $line = $this->config('views.' . $this->ui . '.modal');
        if (empty($line))
        {
            // The config line is not defined, so look for it on the esensi::core package
            $line =  $this->config('esensi/core::core.views.' . $this->ui . '.modal');
        }
        $this->layout = $line;

        $this->setupLayout();

        // Just return the response from the proxy call
        return $this->content($key, $data, $name);
    }

    /**
     * Resolve the templates for an email template.
     *
     * @param string $key to view config
     * @return string|array
     * @throws InvalidArgumentException
     */
    public function email($key)
    {
        // Get the confg line for the view
        $config = 'emails.' . $this->ui . '.' . $key;
        $lines = $this->config($config);
        if( is_null($lines) )
        {
            throw new InvalidArgumentException('Email config line ['.$config.'] not found.');
        }

        return $lines;
    }

    /**
     * Get a configuration line.
     *
     * @param string $key to config line
     * @param mixed $default (optional)
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->resolve(App::make('config'), $key, $default);
    }

    /**
     * Get a TTL configuration line.
     *
     * @param string $key to config line
     * @param mixed $default (optional)
     * @return mixed
     */
    public function ttl($key, $default = null)
    {
        return $this->config('ttl.' . $key, $default);
    }

    /**
     * Get a language line.
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    public function language($key, array $replacements = [])
    {
        return $this->resolve(App::make('translator'), $key, $replacements);
    }

    /**
     * Get an error language line.
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    public function error($key, array $replacements = [])
    {
        return $this->language('errors.' . $key, $replacements);
    }

    /**
     * Get a message language line.
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    public function message($key, array $replacements = [])
    {
        return $this->language('messages.' . $key, $replacements);
    }

    /**
     * Get an option language line.
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    public function option($key, array $replacements = [])
    {
        return $this->language('options.' . $key, $replacements);
    }

    /**
     * Get a subject language line.
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    public function subject($key, array $replacements = [])
    {
        return $this->language('subjects.' . $key, $replacements);
    }

    /**
     * Generate a redirect.
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return Illuminate\Routing\Redirector
     */
    public function redirect($key, array $params = [])
    {
        // Redirect to intended route
        $route = $this->config('redirects.' . $this->ui . '.' . $key, $key);
        return App::make('redirect')->route($route, $params);
    }

    /**
     * Generate a redirect back.
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return Illuminate\Routing\Redirector
     */
    public function back($key, array $params = [])
    {
        // Short circuit to referrer URL or follow redirect
        $referer = App::make('request')->header('referer');
        $redirect = ! empty($referer) ? App::make('redirect')->back() : $this->redirect($key, $params);
        return $redirect;
    }

    /**
     * Combine the event name with the namespace of the package.
     *
     * @param string $name of event
     * @return string
     */
    public function getNamespacedEventName($name)
    {
        $namespace = trim($this->namespacing(), '::');
        $namespace = ! empty($namespace) ? $namespace . '.' : '';
        return strtolower($namespace . $this->package . '.' . $name);
    }

    /**
     * Fire a namespaced event until the first non-null response.
     *
     * @param string $name of event to fire
     * @param array $arguments (optional) to pass to event
     * @return mixed
     */
    public function eventUntil($name, array $arguments = [])
    {
        return App::make('events')->until($this->getNamespacedEventName($name), $arguments);
    }

    /**
     * Fire a namespaced event.
     *
     * @param string $name of event to fire
     * @param array $arguments (optional) to pass to event
     * @return mixed
     */
    public function eventFire($name, array $arguments = [])
    {
        return App::make('events')->fire($this->getNamespacedEventName($name), $arguments);
    }

    /**
     * Queue a namespaced event.
     *
     * @param string $name of event to queue
     * @param array $arguments (optional) to pass to event
     * @return mixed
     */
    public function eventQueue($name, array $arguments = [])
    {
        return App::make('events')->queue($this->getNamespacedEventName($name), $arguments);
    }

    /**
     * Flush namespaced events.
     *
     * @param string $name of event to flush
     * @return mixed
     */
    public function eventFlush($name)
    {
        return App::make('events')->flush($this->getNamespacedEventName($name));
    }

}
