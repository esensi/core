<?php namespace Esensi\Core\Contracts;

/**
 * Packaged interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface PackagedInterface{

    /**
     * Generate a subview for the layout
     *
     * @param string $key to view config
     * @param array $data (optional) to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    function content($key, array $data = [], $name = null);

    /**
     * Generate a modal view
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
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
     * Get the package namespace
     *
     * @return string
     */
    function namespacing();

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
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    function redirect($key, array $params = []);

    /**
     * Generate a redirect back
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    function back($key, array $params = []);

}