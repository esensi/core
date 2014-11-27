<?php namespace Esensi\Core\Traits;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\NamespacedItemResolver as Resolver;

/**
 * Traits for helping with package configurations
 *
 * @author daniel <daniel@bexarcreative.com>
 * @author diego <diego@emersonmedia.com>
 * @see \Esensi\Core\Contracts\PackagedInterface
 */
trait PackagedTrait{

    /**
     * The namespace that should be used by the package.
     *
     * @var string
     */
    protected $namespacing = 'esensi/core';

    /**
     * The package name
     *
     * @var string
     */
    protected $package = 'core';

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
     * Get the package namespace
     *
     * @return string
     */
    protected function namespacing()
    {
        return $this->namespacing ? trim($this->namespacing, '::') . '::' : '';
    }

    /**
     * Resolve a namespaced line
     *
     * @param \Illuminate\Support\NamespacedItemResolver $resolver
     * @param string $key to config line
     * @param mixed $arguments (optional)
     * @return string
     */
    protected function resolve(Resolver $resolver, $key, $arguments = null)
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
     * Generate a subview for the layout
     *
     * @param string $key to view config
     * @param array $data (optional) to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    protected function content($key, array $data = [], $name = null)
    {
        // Assign a default content name
        $name = is_null($name) ? 'content' : $name;

        // Get the confg line for the view
        $line = $this->config('views.' . $this->ui . '.' . $key);

        // Nest the view into the layout
        $view = App::make('view')->make($line, $data);
        $this->layout->$name = $view;

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
    protected function modal($key, array $data = [], $name = null)
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
     * Get a configuration line
     *
     * @param string $key to config line
     * @param mixed $default (optional)
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        return $this->resolve(App::make('config'), $key, $default);
    }

    /**
     * Get a TTL configuration line
     *
     * @param string $key to config line
     * @param mixed $default (optional)
     * @return mixed
     */
    protected function ttl($key, $default = null)
    {
        return $this->config('ttl.' . $key, $default);
    }

    /**
     * Get a language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function language($key, array $replacements = [])
    {
        return $this->resolve(App::make('translator'), $key, $replacements);
    }

    /**
     * Get an error language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function error($key, array $replacements = [])
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
    protected function message($key, array $replacements = [])
    {
        return $this->language('messages.' . $key, $replacements);
    }

    /**
     * Get an option language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function option($key, array $replacements = [])
    {
        return $this->language('options.' . $key, $replacements);
    }

    /**
     * Get a subject language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function subject($key, array $replacements = [])
    {
        return $this->language('subjects.' . $key, $replacements);
    }

    /**
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    protected function redirect($key, array $params = [])
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
    protected function back($key, array $params = [])
    {
        // Short circuit to referrer URL or follow redirect
        $referer = App::make('request')->header('referer');
        $redirect = ! empty($referer) ? App::make('redirect')->back() : $this->redirect($key, $params);
        return $redirect;
    }

    /**
     * Make the name of the event from the called class.
     *
     * @example "Esensi/Class/Foo" returns "esensi"
     *
     * @param string $name of event
     * @return string
     */
    protected function getNamespacedEventName($name)
    {
        $namespace = head(explode('\\', strtolower(get_called_class())));
        return $namespace . '.' . $this->package . '.' . $name;
    }

    /**
     * Fire a namespaced event until the first non-null response.
     *
     * @param string $name of event to fire
     * @param array $arguments (optional) to pass to event
     * @return mixed
     */
    protected function eventUntil($name, array $arguments = [])
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
    protected function eventFire($name, array $arguments = [])
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
    protected function eventQueue($name, array $arguments = [])
    {
        return App::make('events')->queue($this->getNamespacedEventName($name), $arguments);
    }

    /**
     * Flush namespaced events.
     *
     * @param string $name of event to flush
     * @return mixed
     */
    protected function eventFlush($name)
    {
        return App::make('events')->flush($this->getNamespacedEventName($name));
    }

}
