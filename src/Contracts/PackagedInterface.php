<?php namespace Esensi\Core\Contracts;

/**
 * Packaged interface
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface PackagedInterface{

    /**
     * Get the package name
     *
     * @return string
     */
    public function getPackage();

    /**
     * Set the package name
     *
     * @param string $package
     * @return void
     */
    public function setPackage( $package );

    /**
     * Get the UI name
     *
     * @return string
     */
    public function getUI();

    /**
     * Set the UI name
     *
     * @param string $ui
     * @return void
     */
    public function setUI( $ui );

    /**
     * Get the namespace name
     *
     * @return string
     */
    public function getNamespacing();

    /**
     * Set the namespace name
     *
     * @param string $namespace
     * @return void
     */
    public function setNamespacing( $namespace );

    /**
     * Get the package namespace.
     *
     * @return string
     */
    function namespacing();

    /**
     * Resolve a namespaced line.
     *
     * @param Illuminate\Support\NamespacedItemResolver $resolver
     * @param string $key to config line
     * @param mixed $arguments (optional)
     * @return string
     */
    function resolve(Resolver $resolver, $key, $arguments = null);

    /**
     * Generate a subview for the layout
     *
     * @param string $key to view config
     * @param array $data (optional) to be passed to view
     * @param string $name (optional) of content
     * @return Illuminate\View\View
     */
    function content($key, array $data = [], $name = null);

    /**
     * Generate a modal view
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @param string $name (optional) of content
     * @return Illuminate\View\View
     */
    function modal($key, array $data = [], $name = null);

    /**
     * Get a configuration line
     *
     * @param string $key to config line
     * @param mixed $default (optional)
     * @return mixed
     */
    function config($key, $default = null);

    /**
     * Get a TTL configuration line
     *
     * @param string $key to config line
     * @param mixed $default (optional)
     * @return mixed
     */
    function ttl($key, $default = null);

    /**
     * Get a language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function language($key, array $replacements = []);

    /**
     * Get an error language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function error($key, array $replacements = []);

    /**
     * Get a message language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function message($key, array $replacements = []);

    /**
     * Get an option language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function option($key, array $replacements = []);

    /**
     * Get an subject language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function subject($key, array $replacements = []);

    /**
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return Illuminate\Routing\Redirector
     */
    function redirect($key, array $params = []);

    /**
     * Generate a redirect back
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return Illuminate\Routing\Redirector
     */
    function back($key, array $params = []);

    /**
     * Fire a namespaced event until the first non-null response.
     *
     * @param string $name of event to fire
     * @param array $arguments (optional) to pass to event
     * @return mixed
     */
    function eventUntil($name, array $arguments = []);

    /**
     * Fire a namespaced event.
     *
     * @param string $name of event to fire
     * @param array $arguments (optional) to pass to event
     * @return mixed
     */
    function eventFire($name, array $arguments = []);

    /**
     * Queue a namespaced event.
     *
     * @param string $name of event to queue
     * @param array $arguments (optional) to pass to event
     * @return mixed
     */
    function eventQueue($name, array $arguments = []);

    /**
     * Flush namespaced events.
     *
     * @param string $name of event to flush
     * @return mixed
     */
    function eventFlush($name);

}
